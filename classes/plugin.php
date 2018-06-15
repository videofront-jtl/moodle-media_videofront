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
 * @package   media_videofront
 * @copyright 2018 Eduardo Kraus  {@link http://videofront.com.br}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Create VideoFront api.
 */
class media_videofront_plugin extends core_media_player_external {

    /**
     * List supported urls.
     *
     * @param array $urls
     * @param array $options
     * @return array
     */
    public function list_supported_urls(array $urls, array $options = array()) {
        $result = array();
        foreach ($urls as $url) {
            // If VideoTeca support is enabled, URL is supported.
            if (($url->get_scheme() === 'videoteca')) {
                $result[] = $url;
                continue;
            }
        }

        return $result;
    }

    /**
     * Embed external.
     *
     * @param moodle_url $url
     * @param string $name
     * @param int $width
     * @param int $height
     * @param array $options
     * @return string
     * @throws dml_exception
     */
    protected function embed_external(moodle_url $url, $name, $width, $height, $options) {
        global $USER, $COURSE, $CFG;

        // Load config by mod_videofront.
        $config = get_config('videofront');

        $safetyplayer = "";
        if ($config->safety) {
            $safety = $config->safety;
            if (strpos($safety, "profile") === 0) {
                $safety = str_replace("profile_", "", $safety);
                $safetyplayer = $USER->profile->$safety;
            } else {
                $safetyplayer = $USER->$safety;
            }
        }

        $identifier = str_replace('videoteca://', '', $url);

        require($CFG->dirroot . '/mod/videofront/classes/video.php');
        return video::getplayer($COURSE->id, $identifier, $safetyplayer);
    }

    /**
     * Supports Text.
     *
     * @param array $usedextensions
     * @return mixed|string
     */
    public function supports($usedextensions = []) {
        return get_string('support_videoteca', 'media_videofront');
    }

    /**
     * Get embeddable markers.
     *
     * @return array
     */
    public function get_embeddable_markers() {
        $markers = parent::get_embeddable_markers();
        $markers[] = 'videoteca://';
        $markers[] = 'videofront.com.br';

        return $markers;
    }

    /**
     * Default rank
     *
     * @return int
     */
    public function get_rank() {
        return 1;
    }
}
