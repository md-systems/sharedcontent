; ----------------
; Drush Make file to set up a Shared Content capable drupal environment.
; ----------------

api = 2
core = 7.x
projects[drupal][version] = 7.18

; Dependency modules for Shared Content
; --------

projects[colorbox] = 2.3

projects[ctools] = 1.x-dev

projects[entity] = 1.0
projects[entity][patch][] = http://drupal.org/files/entity-1796110-3.patch

projects[entityreference] = 1.0

projects[features] = 1.0

projects[flag] = "2.0"

projects[libraries] = 2.0

projects[rules] = 2.2

projects[views][version] = 3.5
; Only if drupal > 7.16
projects[views][patch][] = http://drupal.org/files/views-exposed-form-reset-redirect-1807916-4.patch

projects[views_bulk_operations] = 3.0

projects[panels] = "3.3"

projects[sharedcontent][type] = "module"
projects[sharedcontent][download][type] = "git"
// @todo: Change to dev snapshot
projects[sharedcontent][download][url] = "http://git.drupal.org/project/sharedcontent.git"
projects[sharedcontent][download][branch] = "7.x-1.x"

projects[search_api][version] = 1.4
projects[search_api][patch][] = "http://drupal.org/files/1850838-1.patch"

; Needs to be a search back end that support the feature 'search_api_mlt'.
; As of 2013-01-17 http://drupal.org/node/1254698 states that only Solr
; and Sphinx supports this.
projects[search_api_solr] = 1.0-rc3

projects[services][version] = 3.3
projects[services][patch][] = http://drupal.org/files/services-rest_server-Spyc-make_documentation_check_libraries-1349588-37.patch

projects[services_client] = 1.x-dev

projects[services_views][version] = 1.x-dev
projects[services_views][patch][] = "http://drupal.org/files/services_views-filter.patch"

libraries[SolrPhpClient][download][type] = "file"
libraries[SolrPhpClient][download][url] = "http://solr-php-client.googlecode.com/files/SolrPhpClient.r60.2011-05-04.tgz"

libraries[yos-social-php][download][type] = "git"
libraries[yos-social-php][download][url] = "https://github.com/yahoo/yos-social-php.git"

; Dependency modules for the testing environment
; --------

projects[devel] = 1.3

projects[past] = "1.x-dev"
