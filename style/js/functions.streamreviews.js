function confirmDelete() {
  $('a[href*="/delete/"]').click(function(e){
      e.preventDefault();
    var checkstr =  confirm('Are you sure you want to delete this?');

    if(checkstr == true){
      window.location = $(this).attr('href');
    }
  });
}