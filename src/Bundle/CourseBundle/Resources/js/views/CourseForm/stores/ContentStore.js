// @flow
import type {IObservableValue} from 'mobx'; // eslint-disable-line import/named
import {toJS} from 'mobx';
import type {ObservableOptions} from 'sulu-admin-bundle/stores/ResourceStore/types';
import {ResourceStore} from 'sulu-admin-bundle/stores';

export default class ContentStore {
    resourceStore: ResourceStore;

    constructor(resourceStore: ResourceStore) {
        this.resourceStore = resourceStore;
    }

    load = () => {
        this.resourceStore.load();
    };

    handleResponse = (response: Object) => {
        this.resourceStore.handleResponse(response);
    };

    setLoading(loading: boolean) {
        this.resourceStore.setLoading(loading);
    }

    setLocale(locale: string) {
        this.resourceStore.setLocale(locale);
    }

    save(options: Object = {}): Promise<*> {
        return this.resourceStore.save(options);
    }

    create(options: Object): Promise<*> {
        return this.resourceStore.create(options);
    }

    update(options: Object): Promise<*> {
        return this.resourceStore.update(options);
    }

    delete(): Promise<*> {
        return this.resourceStore.delete();
    }

    set(name: string, value: mixed) {
        if (name === 'template') {
            return this.resourceStore.set(name, value);
        }

        const content = toJS(this.resourceStore.data.content);
        content[name] = value;

        this.resourceStore.set('content', content);
    }

    setMultiple(data: Object) {
        const content = toJS(this.resourceStore.data.content);

        this.resourceStore.set('content', {...content, ...data});
    }

    change(name: string, value: mixed) {
        if (name === 'template') {
            return this.resourceStore.set(name, value);
        }

        const content = toJS(this.resourceStore.data.content);
        content[name] = value;

        this.resourceStore.change('content', content);
    }

    changeMultiple(data: Object) {
        const content = toJS(this.resourceStore.data.content);

        this.resourceStore.changeMultiple({content: {...content, ...data}});
    }

    clone() {
        return new ContentStore(this.resourceStore.clone());
    }

    get resourceKey(): string {
        return this.resourceStore.resourceKey;
    }

    get id(): ?string | number {
        return this.resourceStore.id;
    }

    get observableOptions(): ObservableOptions {
        return this.resourceStore.observableOptions;
    }

    get loading(): boolean {
        return this.resourceStore.loading;
    }

    get saving(): boolean {
        return this.resourceStore.saving;
    }

    get data(): Object {
        const content = this.resourceStore.data.content;

        return {
            ...content,
            template: this.resourceStore.data.template,
        };
    }

    set data(data: Object) {
        this.resourceStore.set('template', data.template);

        delete data.template;
        this.resourceStore.set('content', data);
    }

    get dirty(): boolean {
        return this.resourceStore.dirty;
    }

    get loadOptions(): Object {
        return this.resourceStore.loadOptions;
    }

    get idQueryParameter(): ?string {
        return this.resourceStore.idQueryParameter;
    }

    get preventLoadingOnce(): boolean {
        return this.resourceStore.preventLoadingOnce;
    }

    get locale(): ?IObservableValue<string> {
        return this.resourceStore.locale;
    }

    destroy() {
        this.resourceStore.destroy();
    }

    handleIdQueryParameterResponse(response: Object) {
        this.resourceStore.handleIdQueryParameterResponse(response);
    }
}
