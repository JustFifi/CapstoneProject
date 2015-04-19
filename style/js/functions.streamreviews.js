(function($){

  // Defining our jQuery plugin

  $.fn.modal_box = function(prop){

    // Default parameters

    var options = $.extend({
      height : "250",
      width : "500",
      title:"JQuery Modal Box Demo",
      description: "Example of how to create a modal box.",
      top: "20%",
      left: "30%",
    },prop);

    return this.click(function(e){
      add_block_page();
      add_popup_box();
      add_styles();

      $('.paulund_modal_box').fadeIn();
    });

     function add_styles(){
      $('.paulund_modal_box').css({
        'position':'absolute',
        'left':options.left,
        'top':options.top,
        'display':'none',
        'height': options.height + 'px',
        'width': options.width + 'px',
        'border':'1px solid #fff',
        'box-shadow': '0px 2px 7px #292929',
        '-moz-box-shadow': '0px 2px 7px #292929',
        '-webkit-box-shadow': '0px 2px 7px #292929',
        'border-radius':'10px',
        '-moz-border-radius':'10px',
        '-webkit-border-radius':'10px',
        'background': '#f2f2f2',
        'z-index':'50',
      });
      $('.paulund_modal_close').css({
        'position':'relative',
        'top':'-25px',
        'left':'20px',
        'float':'right',
        'display':'block',
        'height':'50px',
        'width':'50px',
        'background': 'url(images/close.png) no-repeat',
      });
                        /*Block page overlay*/
      var pageHeight = $(document).height();
      var pageWidth = $(window).width();

      $('.paulund_block_page').css({
        'position':'absolute',
        'top':'0',
        'left':'0',
        'background-color':'rgba(0,0,0,0.6)',
        'height':pageHeight,
        'width':pageWidth,
        'z-index':'10'
      });
      $('.paulund_inner_modal_box').css({
        'background-color':'#fff',
        'height':(options.height - 50) + 'px',
        'width':(options.width - 50) + 'px',
        'padding':'10px',
        'margin':'15px',
        'border-radius':'10px',
        '-moz-border-radius':'10px',
        '-webkit-border-radius':'10px'
      });
    }

     function add_block_page(){
      var block_page = $('<div class="paulund_block_page"></div>');

      $(block_page).appendTo('body');
    }

     function add_popup_box(){
       var pop_up = $('<div class="paulund_modal_box"><a href="#" class="paulund_modal_close"></a><div class="paulund_inner_modal_box"><h2>' + options.title + '</h2><p>' + options.description + '</p></div></div>');
       $(pop_up).appendTo('.paulund_block_page');

       $('.paulund_modal_close').click(function(){
        $(this).parent().fadeOut().remove();
        $('.paulund_block_page').fadeOut().remove();
       });
    }

    return this;
  };
})(jQuery);

function empty(data) {
  if(typeof(data) == 'number' || typeof(data) == 'boolean')
  {
    return false;
  }
  if(typeof(data) == 'undefined' || data === null)
  {
    return true;
  }
  if(typeof(data.length) != 'undefined')
  {
    return data.length == 0;
  }
  var count = 0;
  for(var i in data)
  {
    if(data.hasOwnProperty(i))
    {
      count ++;
    }
  }
  return count == 0;
}

function siteVars(vari) {
  this.url = "http://twitchreviews.tv/";
  this.admin = "admin/";
  this.users = "users/";
  this.variable = vari;
  this.build = buildVar;
}

function buildVar() {
  return this.url + this.admin + this.users + this.variable;
}

function confirmDelete() {
  $('a[href*="/delete/"]').click(function(e){
      e.preventDefault();
    var checkstr =  confirm('Are you sure you want to delete this?');

    if(checkstr == true){
      window.location = $(this).attr('href');
    }
  });
}

function changeText(target, text, clas) {
  $(target).text(text);
  $(target).addClass(clas)
}

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

function searchStreamer(target) {
  $('.form-error, .form-success').remove();
  var data = $(target).val();
  var count = 1;
  $.getJSON("http://twitchreviews.tv/search/twitch-user/" + data, function(data2) {
    if (count == 1) {
      if (!data2) { return true; }
      else { return false; }
      count++;
    }
  });
}

function changeFocus(target) {
  setTimeout(function() {
    $(target).focus();
  }, 500);
}

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

function jsEmailUpdate(element, name, domain, subject) {
  var email = name + '@' + domain;
  var link = '<a href="mailto:' + email + '?subject=' + subject + '">' + email + '</a>';
  $(element).html(link);
}

function makeTextALink(element, link) {
  $(element).each(function(){
    var html = ' <a href="' + link + $(this).html() + '" title="Click to view their Twitch channel" target="_blank" class="glitchIcon"> ' + $(this).html() + '</a>';
    $(this).html(html);
  });
}

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

function submitSearch(field, path) {
  var user = $(field).val();
  if (autoSearchStreamer(user)) {
    window.location.href = "http://twitchreviews.tv/" + path + user;
  }
}