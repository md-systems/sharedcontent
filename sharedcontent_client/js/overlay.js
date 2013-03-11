(function ($) {

  Drupal.behaviors.SharedContentOverlay = {
    attach: function (context, settings) {
        if (!$.isFunction($.colorbox)) {
          return;
        }
        $('.sharedcontent-overlay a.sharedcontent-origin', context)
          .once('init-colorbox-load', function () {
            var parts = $(this)[0].href.match(/([^\?#]+)(\?.+)?(#.+)?/);
            var url = parts[1];
            url += parts[2] ? parts[2] + '&sc[overlay]=true' : '?sc[overlay]=true';
            url += parts[3] ? parts[3] : '';
            var params = {
              'href': url,
              'iframe': true,
              'width': '80%',
              'height': '100%'
            };
            $(this).colorbox($.extend({}, settings.colorbox, params));
          });
    }
  };

})(jQuery);
