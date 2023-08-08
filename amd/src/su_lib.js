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
 * Proctor U File Uploader
 * @modual    local_syllabusuploader
 * @copyright 2023 onwards Louisiana State University
 * @copyright 2023 onwards David Lowe, Robert Russo
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

define(['jquery', 'local_syllabusuploader/jaxy'],
    function($, jaxy) {
    'use strict';
    return {

        /**
         * Store data in sessionStorage so it's available throughout
         *
         * @return null
         */
        preLoadConfig: function() {
            var window_stat = {};

            if (window.__SERVER__ === "true" || window.__SERVER__ === true) {
                if (typeof (window.__INITIAL_STATE__) === 'string') {
                    try {
                        window_stat = JSON.parse(window.__INITIAL_STATE__);
                        delete window.__INITIAL_STATE__;
                        window.__SERVER__ = false;
                    } catch (error) {
                        console.log(error);
                    }
                }
            } else {
                console.log("WARNING: window.__SERVER__ was not set");
            }
            for (var key in window_stat) {
                if (typeof(window_stat[key]) === 'object') {
                    let subkey_list = window_stat[key];
                    for (var subkey in subkey_list) {
                        sessionStorage.setItem(subkey, subkey_list[subkey]);
                    }
                } else {
                    sessionStorage.setItem(key, window_stat[key]);
                }
            }
        },

        /**
         * Post data to a URL. This is a form submit and will cause a page reload POSTing data.
         *
         * @param {string} redirectUrl to post to, self if nothing passed.
         * @param {object} data the json object to post
         * @return {Promise}
         */
        pushPost: function(redirectUrl, data) {

            var input_part = '',
                form_part = '',
                form;

            for (var key in data) {
                var value = data[key];
                input_part += '<input type="hidden" value="' + value + '" name="' + key + '"></input>';
            }

            form_part = '<form method="POST" style="display: none;">' + input_part + '</form>';
            form = $(form_part);
            $('body').append(form);
            $(form).submit();
        },

        /**
         * While developing call this to avoid "SULib is defined but never used."
         *
         * @return {Promise}
         */
        useless: function () {
            console.log("Don't be useless!");
            return;
        },

        /**
         * Get the token for the current selected URL
         *
         * @param {object} params
         * @return {Promise}
         */
        testWebServices: function (params) {
            return this.jaxyRemotePromise(params);
        },

        /**
         * Get the file list.
         *
         * @return {Promise}
         */
        get_file_list: function () {
            return this.jaxyPromise({
                'call': 'get_file_list',
                'class': 'sufile'
            });
        },

        /**
         * Simple request to check if the file already exist.
         *
         * @param {string} params
         * @return {Promise}
         */
        check_file_exists: function (params) {
            var to_send = {
                'mfileid': params.data.mfileid,
                'sufileid': params.data.record
            };
            return this.jaxyPromise({
                'call': 'check_file_exists',
                'params': to_send,
                'class': 'sufile'
            });
        },

        /**
         * Get the token for the current selected URL
         *
         * @param {string} url
         * @return {Promise}
         */
        getTokenForURL: function (url) {
            return this.jaxyPromise({
                'call': 'get_token',
                'params': {
                    'url': url
                },
                'class': 'router'
            });
        },

        /**
         * Check the URL to see if it's valid. This is a work in progress.
         *
         * @param {string} urlString
         * @return {string}
         */
        isValidUrl: function (urlString) {
            var urlPattern = new RegExp('^(https?:\\/\\/)?'+ // validate protocol
            '((([a-z\\d]([a-z\\d-]*[a-z\\d])*)\\.)+[a-z]{2,}|'+ // validate domain name
            '((\\d{1,3}\\.){3}\\d{1,3}))'+ // validate OR ip (v4) address
            '(\\:\\d+)?(\\/[-a-z\\d%_.~+]*)*'+ // validate port and path
            '(\\?[;&a-z\\d%_.~+=-]*)?'+ // validate query string
            '(\\#[-a-z\\d_]*)?$','i'); // validate fragment locator
            return !!urlPattern.test(urlString);
        },

        /* ====================================================================== */
        /* ===================      AJAX Functions      ========================= */
        /* ====================================================================== */
        /**
         * Make an AJAX call and return a json object, from server,
         *  wrapping the AJAX call in a promise.
         * @param {object} params The details of the ajax call.
         * @return {Promise}
         */
        jaxyPromise: function (params) {
            console.log("jaxyPromise() -> what is params to be stringified: ", params);
            var promiseObj = new Promise(function (resolve) {
                jaxy.SUjax(JSON.stringify(params)).then(function (response) {
                    resolve(response);
                });
            });
            return promiseObj;
        },

        /**
         * Make an AJAX call and return a json object, from REMOTE server,
         *  wrapping the AJAX call in a promise.
         * @param {object} params the details of the ajax call
         * @return {Promise}
         */
        jaxyRemotePromise: function (params) {
            var promiseObj = new Promise(function (resolve) {
                jaxy.SURemoteAjax(params).then(function (response) {
                    resolve(response);
                });
            });
            return promiseObj;
        },
    };
});
