$("document").ready(function(){

});

function markNoticeAsRead(el){
    let currentElement = $(el);
    let noticeId = currentElement.data('notice-id');
    let url = '/notice/mark_as_read/'+noticeId;
    $.ajax({
        url: url,
        type: 'POST',
        success: function(response){

            if(response.status == "success"){
                currentElement.parent().parent().remove();
            }
            else{
                alert('Error marking notice as read');
            }
        }
    });
}