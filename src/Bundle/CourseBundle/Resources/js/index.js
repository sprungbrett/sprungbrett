// @flow
import {bundleReady} from 'sulu-admin-bundle/services';
import {viewRegistry} from 'sulu-admin-bundle/containers';
import PageForm from './views/CourseForm';

viewRegistry.add('sprungbrett.course_form', PageForm);

bundleReady();
