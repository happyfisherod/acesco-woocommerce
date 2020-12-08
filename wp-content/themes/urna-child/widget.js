var $ = jQuery.noConflict();
var mediaUploader;
$('input.select-img').click(function(e) {
  e.preventDefault();
    if (mediaUploader) {
    mediaUploader.open();
    return;
  }
  mediaUploader = wp.media.frames.file_frame = wp.media({
    title: 'Choose Image',
    button: {
    text: 'Choose Image'
  }, multiple: false });
  mediaUploader.on('select', function() {
    var attachment = mediaUploader.state().get('selection').first().toJSON();
    $('.img').val(attachment.url);
    
  });
  mediaUploader.open();
});