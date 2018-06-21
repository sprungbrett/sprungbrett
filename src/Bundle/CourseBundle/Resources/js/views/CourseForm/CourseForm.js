// @flow
import React from 'react';
import {action, observable} from 'mobx';
import {observer} from 'mobx-react';
import {Form, FormStore, withToolbar} from 'sulu-admin-bundle/containers';
import type {ViewProps} from 'sulu-admin-bundle/containers';
import {translate} from 'sulu-admin-bundle/utils';
import {ResourceStore} from 'sulu-admin-bundle/stores';
import courseFormStyles from './courseForm.scss';

type Props = ViewProps & {
    resourceStore: ResourceStore,
};

@observer
class CourseForm extends React.Component<Props> {
    resourceStore: ResourceStore;
    formStore: FormStore;
    form: ?Form;
    @observable errors = [];
    showSuccess = observable.box(false);

    constructor(props: Props) {
        super(props);

        const {resourceStore, router} = this.props;

        if (!resourceStore) {
            throw new Error(
                'The view "Form" needs a resourceStore to work properly.'
                + 'Did you maybe forget to make this view a child of a "ResourceTabs" view?'
            );
        }

        this.formStore = new FormStore(resourceStore);

        if (resourceStore.locale) {
            router.bind('locale', resourceStore.locale);
        }
    }

    componentWillUnmount() {
        this.formStore.destroy();
    }

    @action showSuccessSnackbar = () => {
        this.showSuccess.set(true);
    };

    handleSubmit = (actionParameter) => {
        const {resourceStore, router} = this.props;

        const {
            route: {
                options: {
                    editRoute,
                },
            },
        } = router;

        if (editRoute) {
            resourceStore.destroy();
        }

        return this.formStore.save({action: actionParameter})
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
                <Form
                    ref={this.setFormRef}
                    store={this.formStore}
                    onSubmit={this.handleSubmit}
                />
            </div>
        );
    }
}

export default withToolbar(CourseForm, function() {
    const {resourceStore, router} = this.props;
    const {backRoute, locales} = router.route.options;
    const {errors, formStore, showSuccess} = this;

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
                ...(formStore.data.transitions || []).map((transition) => {
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
        formStore.data.workflowStage === 'published' ? 'fa-circle' : 'fa-circle-o',
    ];

    if (formStore.typesLoading || Object.keys(formStore.types).length > 0) {
        items.push({
            type: 'select',
            icon: 'fa-paint-brush',
            onChange: (value) => {
                formStore.changeType(value);
            },
            loading: formStore.typesLoading,
            value: formStore.type,
            options: Object.keys(formStore.types).map((key) => ({
                value: formStore.types[key].key,
                label: formStore.types[key].title,
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
