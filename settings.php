<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Settings file for plugin 'media_videofront'
 *
 * @package   media_videofront
 * @copyright 2016 Marina Glancy
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

if ($ADMIN->fulltree) {

    $mediaplugin = $DB->get_field('filter_active', 'active', array('filter' => 'mediaplugin'));
    $videofrontplugin = strpos($CFG->media_plugins_sortorder, 'videofront');

    if ($videofrontplugin !== false || $mediaplugin == -9999) {
        $settings->add(
            new admin_setting_configcheckbox('media_videofront/autoenable',
                new lang_string('enableauto', 'media_videofront'),
                new lang_string('enableauto_desc', 'media_videofront'), false));

        if ($data = data_submitted() and confirm_sesskey()) {

            $videofrontautoenable = optional_param("s_media_videofront_autoenable", 0, PARAM_INT);
            if ($videofrontautoenable) {
                // Enable Filter.
                $filterpath = 'mediaplugin';
                filter_set_global_state($filterpath, 1);
                filter_set_applies_to_strings($filterpath, 1);

                // Enable Media VideoFront Plugin.
                $plugins = core_plugin_manager::instance()->get_plugins_of_type('media');
                $plugins['videofront']->set_enabled(true);
            }
        }
    }

    if ($videofrontplugin === false) {
        $settings->add(
            new admin_setting_heading ('media_videofront/videofront',
                new lang_string('enablevideofront', 'media_videofront'),
                new lang_string('enablevideofront_desc', 'media_videofront', $CFG->wwwroot)));
    } else {
        $settings->add(
            new admin_setting_heading ('media_videofront/videofront',
                new lang_string('enablevideofront', 'media_videofront'),
                new lang_string('enabledvideofront_desc', 'media_videofront', $CFG->wwwroot)));
    }

    if ($mediaplugin == -9999) {
        $settings->add(
            new admin_setting_heading ('media_videofront/mediaplugin',
                new lang_string('enablemediaplugin', 'media_videofront'),
                new lang_string('enablemediaplugin_desc', 'media_videofront', $CFG->wwwroot)));
    } else {
        $settings->add(
            new admin_setting_heading ('media_videofront/mediaplugin',
                new lang_string('enablemediaplugin', 'media_videofront'),
                new lang_string('enabledmediaplugin_desc', 'media_videofront', $CFG->wwwroot)));
    }
}

