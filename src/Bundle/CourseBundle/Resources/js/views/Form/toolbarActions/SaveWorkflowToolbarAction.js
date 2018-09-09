// @flow
import {translate} from 'sulu-admin-bundle/utils/Translator';
import AbstractToolbarAction from 'sulu-admin-bundle/views/Form/toolbarActions/AbstractToolbarAction';

export default class SaveWorkflowToolbarAction extends AbstractToolbarAction {
    getToolbarItemConfig() {
        const {formStore: {resourceStore}} = this;

        return {
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
        };
    }
}
