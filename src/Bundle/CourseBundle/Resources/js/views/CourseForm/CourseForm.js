// @flow
import React from 'react';
import type {ElementRef} from 'react';
import {action, computed, observable, isObservableArray} from 'mobx';
import {observer} from 'mobx-react';
import equals from 'fast-deep-equal';
import {PublishIndicator} from 'sulu-admin-bundle/components';
import {Form as FormContainer, FormStore, withToolbar} from 'sulu-admin-bundle/containers';
import type {ViewProps} from 'sulu-admin-bundle/containers';
import {ResourceStore} from 'sulu-admin-bundle/stores';
import toolbarActionRegistry from 'sulu-admin-bundle/views/Form/registries/ToolbarActionRegistry';
import ContentStore from './stores/ContentStore';
import courseFormStyles from './courseForm.scss';

type Props = ViewProps & {
    locales: Array<string>,
    resourceStore: ResourceStore,
};

@observer
class CourseForm extends React.Component<Props> {
    resourceStore: ResourceStore;
    formStore: FormStore;
    form: ?ElementRef<typeof FormContainer>;
    @observable errors = [];
    showSuccess = observable.box(false);
    @observable toolbarActions = [];

    @computed get hasOwnResourceStore() {
        const {
            resourceStore,
            route: {
                options: {
                    resourceKey,
                },
            },
        } = this.props;

        return resourceKey && resourceStore.resourceKey !== resourceKey;
    }

    @computed get locales() {
        const {
            locales: propsLocales,
            route: {
                options: {
                    locales: routeLocales,
                },
            },
        } = this.props;

        return routeLocales ? routeLocales : propsLocales;
    }

    constructor(props: Props) {
        super(props);

        const {resourceStore, router} = this.props;
        const {
            attributes,
            route: {
                options: {
                    idQueryParameter,
                    resourceKey,
                    routerAttributesToFormStore,
                    content,
                },
            },
        } = router;
        const {id} = attributes;

        if (!resourceStore) {
            throw new Error(
                'The view "CourseForm" needs a resourceStore to work properly.'
                + 'Did you maybe forget to make this view a child of a "ResourceTabs" view?'
            );
        }

        const formStoreOptions = routerAttributesToFormStore
            ? routerAttributesToFormStore.reduce(
                (options: Object, routerAttribute: string) => {
                    options[routerAttribute] = attributes[routerAttribute];
                    return options;
                },
                {}
            )
            : {};

        if (this.hasOwnResourceStore) {
            let locale = resourceStore.locale;
            if (!locale && this.locales) {
                locale = observable.box();
            }

            this.resourceStore = idQueryParameter
                ? new ResourceStore(resourceKey, id, {locale}, formStoreOptions, idQueryParameter)
                : new ResourceStore(resourceKey, id, {locale}, formStoreOptions);
        } else {
            this.resourceStore = resourceStore;

            if (Object.keys(this.resourceStore.data).length > 0) {
                // data should be reloaded if ResourceTabs ResourceStore is used and user comes back from another tab
                // the above check assumes that loading the data from the backend takes longer than calling this method
                // the very unlikely worst case scenario if this assumption is not met, is that the data is loaded twice
                this.resourceStore.load();
            }
        }

        const formResourceStore = content ? new ContentStore(this.resourceStore) : this.resourceStore;
        // $FlowFixMe
        this.formStore = new FormStore(formResourceStore, formStoreOptions);

        if (this.resourceStore.locale) {
            router.bind('locale', this.resourceStore.locale);
        }
    }

    @action componentDidMount() {
        const {locales, router} = this.props;
        const {
            route: {
                options: {
                    toolbarActions,
                },
            },
        } = router;

        if (!Array.isArray(toolbarActions) && !isObservableArray(toolbarActions)) {
            throw new Error('The view "Form" needs some defined toolbarActions to work properly!');
        }

        this.toolbarActions = toolbarActions.map((toolbarAction) => new (toolbarActionRegistry.get(toolbarAction))(
            this.formStore,
            // $FlowFixMe
            this,
            router,
            locales
        ));
    }

    componentDidUpdate(prevProps: Props) {
        if (!equals(this.props.locales, prevProps.locales)) {
            this.toolbarActions.forEach((toolbarAction) => {
                toolbarAction.setLocales(this.locales);
            });
        }
    }

    componentWillUnmount() {
        this.formStore.destroy();

        if (this.hasOwnResourceStore) {
            this.resourceStore.destroy();
        }
    }

    @action showSuccessSnackbar = () => {
        this.showSuccess.set(true);
    };

    @action submit = (action: ?string) => {
        if (!this.form) {
            throw new Error('The form ref has not been set! This should not happen and is likely a bug.');
        }
        this.form.submit(action);
    };

    handleSubmit = (actionParameter) => {
        const {resourceStore, router} = this.props;

        const {
            attributes,
            route: {
                options: {
                    editRoute,
                    routerAttributesToEditRoute,
                },
            },
        } = router;

        if (editRoute) {
            resourceStore.destroy();
        }

        const saveOptions = {
            action: actionParameter,
        };

        const editRouteParameters = routerAttributesToEditRoute
            ? routerAttributesToEditRoute.reduce(
                (parameters: Object, routerAttribute: string) => {
                    parameters[routerAttribute] = attributes[routerAttribute];
                    return parameters;
                },
                {}
            )
            : {};

        return this.formStore.save(saveOptions)
            .then((response) => {
                this.showSuccessSnackbar();

                if (editRoute) {
                    router.navigate(
                        editRoute,
                        {id: resourceStore.id, locale: resourceStore.locale, ...editRouteParameters}
                    );
                }

                return response;
            })
            .catch((errorResponse) => {
                return errorResponse.json().then(action((error) => {
                    this.errors.push(error);
                }));
            });
    };

    setFormRef = (form) => {
        this.form = form;
    };

    render() {
        return (
            <div className={courseFormStyles.form}>
                <FormContainer
                    ref={this.setFormRef}
                    store={this.formStore}
                    onSubmit={this.handleSubmit}
                />
                {this.toolbarActions.map((toolbarAction) => toolbarAction.getNode())}
            </div>
        );
    }
}

export default withToolbar(CourseForm, function() {
    const {router} = this.props;
    const {backRoute} = router.route.options;
    const {errors, resourceStore, showSuccess} = this;

    const backButton = backRoute
        ? {
            onClick: () => {
                const options = {};
                if (resourceStore.locale) {
                    options.locale = resourceStore.locale.get();
                }
                router.restore(backRoute, options);
            },
        }
        : undefined;
    const locale = this.locales
        ? {
            value: resourceStore.locale.get(),
            onChange: (locale) => {
                resourceStore.setLocale(locale);
            },
            options: this.locales.map((locale) => ({
                value: locale,
                label: locale,
            })),
        }
        : undefined;

    const items = this.toolbarActions
        .map((toolbarAction) => toolbarAction.getToolbarItemConfig())
        .filter((item) => item !== undefined);

    const icons = [];
    const formData = this.resourceStore.data;

    if (formData.hasOwnProperty('workflowStage')) {
        const {workflowStage} = formData;
        icons.push(
            <PublishIndicator
                key={'publish'}
                draft={workflowStage === 'test'}
                published={workflowStage === 'published'}
            />
        );
    }

    return {
        backButton,
        errors,
        locale,
        items,
        icons,
        showSuccess,
    };
});
