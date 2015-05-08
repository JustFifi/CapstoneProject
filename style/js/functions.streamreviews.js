// used in conjunction with admin() to build the ajax links for
// disabling/enabling accounts and moderator status.
function siteVars(vari) {
  this.url = "http://twitchreviews.tv/";
  this.admin = "admin/";
  this.users = "users/";
  this.variable = vari;
  this.build = buildVar;
}

// Used with siteVars() to put the link together
function buildVar() {
  return this.url + this.admin + this.users + this.variable;
}

// Simple confirmation popup to double check
// the admin/moderator wants to delete the blog/review
function confirmDelete() {
  $('a[href*="/delete/"]').click(function(e){
      e.preventDefault();
    var checkstr =  confirm('Are you sure you want to delete this?');

    if(checkstr == true){
      window.location = $(this).attr('href');
    }
  });
}

// Extension of the admin() function to change the text
// of specified elements
function changeText(target, text, clas) {
  $(target).text(text);
  $(target).addClass(clas)
}

// Changes the admin control buttons to match their current state
function admin() {
  window.setTimeout(function(){
    $('p.success').remove();
  }, 5000);

  $('.admin-controls a').click(function(e){
    e.preventDefault();

    var url = this.href;
    split = url.split("/users/");
    var s = new siteVars(split[1]);

    if (($(this).hasClass("usr-admin-1")) || ($(this).hasClass("usr-admin-0"))) {
      if ($(this).hasClass("usr-admin-1")) {
        var nTxt = "Enable Admin";
        $(this).closest("div").find(".type-of-user").text("Reviewer");
        // var nClass = 'usr-admin-0';
        // var oClass = 'usr-admin-1';
      } else if ($(this).hasClass("usr-admin-0")) {
        var nTxt = "Disable Admin";
        $(this).closest("div").find(".type-of-user").text("Moderator");
      }
        $(this).toggleClass("usr-admin-1");
        $(this).toggleClass("usr-admin-0");
        $(this).toggleClass("blue");
        $(this).toggleClass("green");
    } else if (($(this).hasClass("usr-disabled-0")) || ($(this).hasClass("usr-disabled-1"))) {
      if ($(this).hasClass("usr-disabled-1")) {
        var nTxt = "Disable Account";
      } else if ($(this).hasClass("usr-disabled-0")) {
        var nTxt = "Enable Account";
      }
        $(this).toggleClass("usr-disabled-1");
        $(this).toggleClass("usr-disabled-0");
        $(this).closest( "div" ).toggleClass("disabled-0");
        $(this).closest( "div" ).toggleClass("disabled-1");
        $(this).parent().next().children().toggleClass("hideElement");
    }
      $.ajax({
        url: s.build()
      });
    $(this).text(nTxt);
  });
}

// Checks the review form to make sure the twitch target
// is a valid user
function autoSearchStreamer(target) {
  $(target).blur(function(){
    $('.form-error, .form-success').remove();
    var data = $(this).val();
    var count = 1;
    $.getJSON("http://twitchreviews.tv/search/twitch-user/" + data, function(data2) {
      if (count == 1) {
        if (!data2) {
          $(target).prev().append('<span class="form-error">Invalid Twitch User</span>');
          return false;
        } else {
          $(target).prev().append('<span class="form-success">Valid Twitch User</span>');
          return true;
        }
        count++;
      }
    });
  });
}

// Forces the focus on a different element where
// the wysihtml5 editor is used
function changeFocus(target) {
  setTimeout(function() {
    $(target).focus();
  }, 500);
}

// Performs a final check on the review submission so valid information has to be entered.
function checkReviewForm(form, rating, target, user, button) {
  //less than .5 on rating-h
  $(form).submit(function(e){
    var error = 0
    var vUsr = $(target).prev().children('.form-error');
    var rate = $(rating).val();
    if (rate < 0.5) {
      $(rating).prev().append('<span class="form-error">You must select a rating to continue</span>');
      error++;
    }
    if ($(target).val().toLowerCase() == $(user).val().toLowerCase()) {
      $(target).prev().append('<span class="form-error">You cannot review yourself.</span>');
      error++;
    }

    if (vUsr.html() == 'Invalid Twitch User') {
      $(target).prev().append('<span class="form-error">Please fix this field.</span>');
      error++;
    }

    if (error == 0) {
      // Do nothing
    } else {
      e.preventDefault();
      $(button).after('<span class="form-error">There were errors. Please go fix them.</span>');
    }
    error = 0;
  });
}


// Prebuilt function from rateit to show stars instead of number value
$.fn.stars = function() {
    return $(this).each(function() {
        // Get the value
        var val = parseFloat($(this).html());

        val = Math.round(val * 2) / 2; /* To round to nearest half */
        // Make sure that the value is in 0 - 5 range, multiply to get width
        var size = Math.max(0, (Math.min(5, val))) * 16;
        // Create stars holder
        var $span = $('<span />').width(size);
        // Replace the numerical value with stars
        $(this).html($span);
    });
}

// Creates a email link where it's needed to prevent spam bots from grabbing it from the html
function jsEmailUpdate(element, name, domain, subject) {
  var email = name + '@' + domain;
  var link = '<a href="mailto:' + email + '?subject=' + subject + '">' + email + '</a>';
  $(element).html(link);
}

// Converts text to a link
// Used on usernames to link to a persons Twitch Stream page
function makeTextALink(element, link) {
  $(element).each(function(){
    var html = ' <a href="' + link + $(this).html() + '" title="Click to view their Twitch channel" target="_blank" class="glitchIcon"> ' + $(this).html() + '</a>';
    $(this).html(html);
  });
}

// Used on any input field that a twitch name is entered
// Changes spaces, hyphens, plus signs and equals signs to underscores
function fixTwitchUsername(field) {
  $(field).keyup(function() {
    var text = $(this).val();
    $(this).val(
        text.replace(" ", "_")
            .replace("-", "_")
            .replace("+", "_")
            .replace("=", "_")
    );
  });
}