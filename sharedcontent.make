; ----------------
; Drush Make file to set up a Shared Content capable drupal environment.
; ----------------

api = 2
core = 7.x
projects[drupal][version] = 7.18

; Dependency modules for Shared Content
; --------

projects[colorbox] = 2.4

projects[ctools] = 1.3

projects[entity] = 1.0
projects[entity][patch][] = http://drupal.org/files/entity-1796110-3.patch

projects[entityreference] = 1.0

projects[features] = 1.0

projects[flag] = 2.0

projects[libraries] = 2.1

projects[rules] = 2.3

projects[views][version] = 3.7

projects[views_bulk_operations] = 3.1

projects[panels] = 3.3

projects[sharedcontent] = 1.x-dev

projects[search_api][version] = 1.4
projects[search_api][patch][] = http://drupal.org/files/1850838-1.patch

projects[services][version] = 3.3
projects[services][patch][] = http://drupal.org/files/services-rest_server-Spyc-make_documentation_check_libraries-1349588-37.patch

projects[services_client] = 1.0-beta1

libraries[yos-social-php][download][type] = git
libraries[yos-social-php][download][url] = https://github.com/yahoo/yos-social-php.git

; Dependency modules for the testing environment
; --------

projects[devel] = 1.3

projects[past] = 1.0-alpha1

; Needs to be a search back end that support the feature 'search_api_mlt'.
; As of 2013-01-17 http://drupal.org/node/1254698 states that only Solr and
; Sphinx supports this.
; With http://drupal.org/node/1958562 this is no longer a hard dependency and
; we can use search_api_db again for testing. Note no suggestions available.
projects[search_api_db] = 1.0-beta4
