$(document).ready(function(){
  confirmDelete();
  changeText('span .usr-disabled-1', 'Enable Account');
  changeText('span .usr-disabled-0', 'Disable Account');
  changeText('span .usr-admin-0', 'Enable Admin', 'green');
  changeText('span .usr-admin-1', 'Disable Admin', 'blue');
  admin();
  jsEmailUpdate('#cookie-email', 'cookies', 'twitchreviews.tv', 'Inquiry on Cookie Policy');
  makeTextALink('.twitch_username', 'http://twitch.tv/');
  autoSearchStreamer('form #review_target');
  checkReviewForm('#reviewForm', '#rating-h', '#review_target', '#user', '#formSubmit');
  fixTwitchUsername('#review_target');
  fixTwitchUsername('#twitchSearchUser');
  $('li.stars, td.stars').stars();
});