// @flow
import React from 'react';
import {action, computed, isObservableArray, observable} from 'mobx';
import {Form as FormContainer, FormStore, withToolbar} from 'sulu-admin-bundle/containers';
import type {ViewProps} from 'sulu-admin-bundle/containers';
import {translate} from 'sulu-admin-bundle/utils';
import {ResourceStore} from 'sulu-admin-bundle/stores';
import ContentStore from './stores/ContentStore';
import courseFormStyles from './courseForm.scss';

type Props = ViewProps & {
    resourceStore: ResourceStore,
};

class CourseForm extends React.PureComponent<Props> {
    resourceStore: ResourceStore;
    formStore: FormStore;
    form: ?FormContainer;
    @observable errors = [];
    showSuccess = observable.box(false);

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

    constructor(props: Props) {
        super(props);

        const {resourceStore, router} = this.props;
        const {
            attributes: {
                id,
            },
            route: {
                options: {
                    idQueryParameter,
                    resourceKey,
                    locales,
                    content,
                },
            },
        } = router;

        if (!resourceStore) {
            throw new Error(
                'The view "CourseForm" needs a resourceStore to work properly.'
                + 'Did you maybe forget to make this view a child of a "ResourceTabs" view?'
            );
        }

        if (this.hasOwnResourceStore) {
            let locale = resourceStore.locale;
            if ((typeof locales === 'boolean' && locales === true)) {
                locale = observable.box();
            }

            if ((Array.isArray(locales) || isObservableArray(locales)) && locales.length > 0) {
                const parentLocale = resourceStore.locale ? resourceStore.locale.get() : undefined;
                if (locales.includes(parentLocale)) {
                    locale = observable.box(parentLocale);
                } else {
                    locale = observable.box();
                }
            }

            this.resourceStore = idQueryParameter
                ? new ResourceStore(resourceKey, id, {locale: locale}, {}, idQueryParameter)
                : new ResourceStore(resourceKey, id, {locale: locale});
        } else {
            this.resourceStore = resourceStore;
        }

        const formResourceStore = content ? new ContentStore(this.resourceStore) : this.resourceStore;
        // $FlowFixMe
        this.formStore = new FormStore(formResourceStore);

        if (this.resourceStore.locale) {
            router.bind('locale', this.resourceStore.locale);
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

    handleSubmit = (actionParameter) => {
        const {resourceStore, router} = this.props;

        const {
            attributes: {
                parentId,
            },
            route: {
                options: {
                    editRoute,
                },
            },
        } = router;

        if (editRoute) {
            resourceStore.destroy();
        }

        return this.formStore.save({parentid: parentId, action: actionParameter})
            .then((response) => {
                this.showSuccessSnackbar();
                if (editRoute) {
                    router.navigate(editRoute, {id: resourceStore.id, locale: resourceStore.locale});
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
            </div>
        );
    }
}

export default withToolbar(CourseForm, function() {
    const {router} = this.props;
    const {backRoute, locales} = router.route.options;
    const formTypes = this.formStore.types;
    const {errors, resourceStore, formStore, showSuccess} = this;

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
    const locale = locales
        ? {
            value: resourceStore.locale.get(),
            onChange: (locale) => {
                resourceStore.setLocale(locale);
            },
            options: locales.map((locale) => ({
                value: locale,
                label: locale,
            })),
        }
        : undefined;

    const items = [
        {
            type: 'dropdown',
            label: translate('sprungbrett.save'),
            icon: 'su-save',
            loading: resourceStore.saving,
            options: [
                {
                    label: translate('sprungbrett.save'),
                    disabled: !resourceStore.dirty,
                    onClick: () => {
                        this.form.submit('draft');
                    },
                },
                ...(resourceStore.data.transitions || []).map((transition) => {
                    return {
                        label: translate('sprungbrett.save_' + transition.name),
                        onClick: () => {
                            this.form.submit(transition.name);
                        },
                    };
                }),
            ],
        },
    ];

    const icons = [
        resourceStore.data.workflowStage === 'published' ? 'fa-circle' : 'fa-circle-o',
    ];

    if (formStore.typesLoading || Object.keys(formTypes).length > 0) {
        items.push({
            type: 'select',
            icon: 'fa-paint-brush',
            onChange: (value) => {
                formStore.changeType(value);
            },
            loading: formStore.typesLoading,
            value: formStore.type,
            options: Object.keys(formTypes).map((key) => ({
                value: formTypes[key].key,
                label: formTypes[key].title,
            })),
        });
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
