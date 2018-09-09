// @flow
import {bundleReady} from 'sulu-admin-bundle/services';
import {viewRegistry} from 'sulu-admin-bundle/containers';
import {toolbarActionRegistry} from 'sulu-admin-bundle/views';
import CourseForm from './views/CourseForm';
import SaveWorkflowToolbarAction from './views/Form/toolbarActions/SaveWorkflowToolbarAction';

viewRegistry.add('sprungbrett.form', CourseForm);

toolbarActionRegistry.add('sprungbrett.save_workflow', SaveWorkflowToolbarAction);

bundleReady();
