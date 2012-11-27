var comment_button;
var delete_comment;
var delete_status;
var like_comment;
var unlike_comment;
var like_status;
var unlike_status;
var user_image;
var likedetails_comment;
var likedetails_status;

function init()
{
    comment_button = $('.feed .comment-box input.button');
    delete_comment = $('.feed .comment-wrapper .comment .delete');
    delete_status = '.feed .status .quote .delete';
    like_comment = $('.feed .comment-wrapper .comment .like-details .like:contains("Like")');
    unlike_comment = $('.feed .comment-wrapper .comment .like-details .like:contains("Unlike")');
    like_status = $('.feed .status .like-details .like:contains("Like")');
    unlike_status = $('.feed .status .like-detials .like:contains("Unlike")');
    user_image = $('.feed .userpic');
    likedetails_comment = '.feed .comment-wrapper .comment .like-details span';
    likedetails_status = '.feed .status .like-details span';
}

$(document).ready(function(){

    init();

    //post comment on status
    $(comment_button).click(function(){
        tar_comment_box = $(this).parent('.comment-box');
        comment_text = $(tar_comment_box).find('textarea[name=comment]').val().trim();
        if(comment_text == '')
            alert('Cannot be a empty comment');
        else
            postComment(comment_text);
    });

    //checking who likes comment
    $('.feed').on("click", likedetails_comment, function(){
        comment_id = $(this).parent('.like-details').parent('.comment').parent('.comment-wrapper').attr('data-comment');
        getLikeDetails("comment",comment_id);
    });
    
    //checking who likes status
    $('.feed').on("click", likedetails_status, function(){
        status_id = $(this).parent('.like-details').parent('.status').parent('.feed').attr('data-status');
        getLikeDetails("status",status_id);
    });
    
    //delete status
    $('.feed').on("click", delete_status,function(){
        status_id = $(this).parent('.quote').parent('.status').parent('.feed').attr('data-status');
        deleteStatus(status_id);
    });

    //delete comment function
    $('.feed').on("click", '.comment-wrapper .comment .delete', function(){
        comment_id = $(this).parent('.quote').parent('.comment').parent('.comment-wrapper').attr('data-comment');
        deleteComment(comment_id);
    });

    //like on comments
    $('.feed').on("click",'.comment-wrapper .comment .like-details .like:contains("Like")', function(){
        comment_id = $(this).parent('.like-details').parent('.comment').parent('.comment-wrapper').attr('data-comment');
        like("comment",comment_id);
    });

    //unlike on comments
    $('.feed').on("click",'.comment-wrapper .comment .like-details .like:contains("Unlike")', function(){
        comment_id = $(this).parent('.like-details').parent('.comment').parent('.comment-wrapper').attr('data-comment');
        unlike("comment",comment_id);
    });

    //like on status
    $('.feed').on("click",'.status .like-details .like:contains("Like")', function(){
        status_id = $(this).parent('.like-details').parent('.status').parent('.feed').attr('data-status');
        like("status",status_id);
    });

    //unlike on status
    $('.feed').on("click",'.status .like-details .like:contains("Unlike")', function(){
        status_id = $(this).parent('.like-details').parent('.status').parent('.feed').attr('data-status');
        unlike("status",status_id);
    });

    // Setting profile pic
    $(user_image).each(function(){
        url=$(this).attr('src');
        checkAndReplaceImage(url, '.feed .userpic');
    });
});

function checkAndReplaceImage(image_url, userpic_selector)
{
    $.ajax({
        url:image_url,
        type:'HEAD',
        async: false,
        error:
        function(){
            $(userpic_selector+'[src="'+image_url+'"]')
                .attr('src', 'views/helpers/images/profilepics/default.jpg');
        },
        success:
        function(){
            return true;
        }
    });
}
//get the people who likes
function getLikeDetails(type,item_id)
{
    if(type == 'comment' || type== 'status')
      {
        //Ajax - jquery
        $.ajax({
            type: 'POST',
            url: 'index.php?rt=index/getLikeDetails',
            data: 'type='+type+'&item_id='+item_id,
            success: function(data, status, xhr){
            console.log ('data:' +data);
            if(data != '')
            {
                //PARSE STRING FOR JSON DATA
                jsonObj = JSON.parse(data);

                noOfLikeDetails = jsonObj.like_details.length;

                console.log('No. of likes: '+noOfLikeDetails);

                likeDetails_html = '<div style="width: 100%"><ul>';

                for(i=0; i<noOfLikeDetails; i++)
                {
                    profile_id = jsonObj.like_details[i].profile_id;
                    displayname = jsonObj.like_details[i].displayname;

                    likeDetails_html += '<li>'
                            +'<img class="userpic" src="views/helpers/images/profilepics/'+ profile_id +'.jpg" width="62"/>'
                        +'<span>'
                            +displayname
                        +'</span></li>'
                }

                likeDetails_html += '</ul></div>';

                window_wt = $(window).outerWidth();
                window_ht = $(window).outerHeight();

                $('.modal_window_likeDetails').html(likeDetails_html);

                $('.modal_window_likeDetails .userpic').each(function(){
                        url=$(this).attr('src');
                        checkAndReplaceImage(url, '.modal_window_likeDetails .userpic');
                    });

                $('.modal_overlay').removeClass('hide');

                modal_wt = $('.modal_window_likeDetails').outerWidth();
                modal_ht = $('.modal_window_likeDetails').outerHeight();

                top_pos = (window_ht - modal_ht)/2;
                left_pos = (window_wt - modal_wt)/2;

                console.log(modal_wt +','+modal_ht+','+top_pos+','+left_pos);

                $('.modal_window_likeDetails').css({
                    top: top_pos+'px',
                    left: left_pos+'px',
                    position: 'absolute'
                });
            }
            else{
            // exception part
            }
        }
        });
    }
}

function like(type,item_id)
{
    if(type=='status')
    {
        //Ajax - jquery
        $.ajax({
            type: 'POST',
            url: 'index.php?rt=index/like',
            data: 'type='+type+'&item_id='+item_id,
            success: function(data, status, xhr){
                console.log ('data:' +data);
                if(/^[0-9]+$/.test(data)){
                    //success
                    tar_status = $('.feed[data-status='+item_id+'] .status');
                    no_of_likes = $(tar_status).find('.like-details span');

                    // Changing like to unlike
                    $(tar_status).find('.like-details .like').text('Unlike');

                    if($(no_of_likes).text().trim() == '')
                        $(no_of_likes).text(' You like this');
                    else
                        $(no_of_likes).text(' You and '+$(no_of_likes).text().trim());
                }
                else{
                // exception part
                }
            }
        });
    }
    else if(type=='comment')
    {
        //Ajax - jquery
        $.ajax({
            type: 'POST',
            url: 'index.php?rt=index/like',
            data: 'type='+type+'&item_id='+item_id,
            success: function(data, status, xhr){
                console.log ('data:' +data);
                if(/^[0-9]+$/.test(data)){
                    //success
                    tar_comment = $('.comment-wrapper[data-comment='+item_id+'] .comment');
                    no_of_likes = $(tar_comment).find('.like-details span');

                    // Changing like to unlike
                    $(tar_comment).find('.like-details .like').text('Unlike');

                    if($(no_of_likes).text().trim() == '')
                        $(no_of_likes).text('You like this');
                    else
                        $(no_of_likes).text('You and '+$(no_of_likes).text().trim());
                }
                else{
                // exception part
                }
            }
        });
    }
}
function unlike(type,item_id)
{
    if(type=='status')
    {
        //Ajax - jquery
        $.ajax({
            type: 'POST',
            url: 'index.php?rt=index/unlike',
            data: 'type='+type+'&item_id='+item_id,
            success: function(data, status, xhr){
                console.log ('data:' +data);
                if(/^[0-9]+$/.test(data)){
                    //success
                    tar_status = $('.feed[data-status='+item_id+'] .status');
                    no_of_likes = $(tar_status).find('.like-details span');

                    // Changing Unlike to like
                    $(tar_status).find('.like-details .like').text('Like');

                    if($(no_of_likes).text().trim() == 'You like this')
                        $(no_of_likes).text('');

                    else
                        str = $(no_of_likes).text().trim();
                    $(no_of_likes).text(str.substr(7,26));
                }
                else{
                // exception part
                }
            }
        });
    }
    else if(type=='comment')
    {
        //Ajax - jquery
        $.ajax({
            type: 'POST',
            url: 'index.php?rt=index/unlike',
            data: 'type='+type+'&item_id='+item_id,
            success: function(data, status, xhr){
                console.log ('data:' +data);
                if(/^[0-9]+$/.test(data)){
                    //success
                    tar_comment = $('.comment-wrapper[data-comment='+item_id+'] .comment');
                    no_of_likes = $(tar_comment).find('.like-details').find('span');

                    // Changing unlike to like
                    $(tar_comment).find('.like-details .like').text('Like');

                    if($(no_of_likes).text().trim() == 'You like this')
                        $(no_of_likes).text('');

                    else
                        str = $(no_of_likes).text().trim();
                    $(no_of_likes).text(str.substr(7,26));
                }
                else{
                // exception part
                }
            }
        });
    }
}


function postComment(comment_text)
{
    status_id = $(tar_comment_box).parent('.feed').attr('data-status');
    display_name = $('#profile_header div div h1').text();
    profile_pic = $('#envelope div div div a img').attr('src');


    //disabling comment button
    $(comment_button).attr('disabled','disabled');

    def_button_value = $(comment_button).val();
    $(comment_button).val('Posting...');

    encoded_comment = encodeURIComponent(comment_text);

    //Ajax - jquery
    $.ajax({
        type: 'POST',
        url: 'index.php?rt=index/commentOnStatus',
        data: 'status_id='+status_id+'&comment='+encoded_comment,
        success: function(data, status, xhr) {
            if(/^[0-9]+$/.test(data)) {
                //success
                $(tar_comment_box).parent('.feed').find('.new-comment').append(getNewCommentCode(comment_text, data));

                $(tar_comment_box).parent('.feed').find('.comment-wrapper[data-comment='+data+']').slideDown(200);

                $(tar_comment_box).find('textarea[name=comment]').val('');
            }
            else {
                //failure
                alert('Cannot save your comment');
            }

            $(comment_button).val(def_button_value);

            //Enable button
            $(comment_button).removeAttr('disabled');
        }
    });
}

function getNewCommentCode(comment_text, comment_id)
{
    myDate = new Date();
    a = 'am';

    hour = myDate.getHours();
    if(hour>12)
    {
        hour = hour - 12;
        if(hour < 10)
            hour = '0' + hour;
        a = 'pm';
    }

    min = myDate.getMinutes();
    if(min < 10)
        min = '0' + min;

    return '<div class="comment-wrapper" style="display:none" data-comment='+comment_id+'>'+
    '<img class="userpic" src=' + profile_pic+ '/>'+
    '<div class="comment">'+
    '<span class="author">' + display_name +'</span>' +
    '<div class="quote">' + comment_text +
    '<span class="date">at '+ hour +':'+min+' '+a+'</span>' + '<a class="delete"></a>' +
    ' </div>' +
    '<div class="like-details">' +
    '<a class="like">Like</a>' +
    '<span></span>' +
    '</div>' +
    '</div>' +
    '<div class="clearer"></div>'
    + '</div>';
}

function deleteComment(comment_id)
{
    if(confirm('Do you want to delete comment?'))
    {
        //Ajax - jquery
        $.ajax({
            type: 'POST',
            url: 'index.php?rt=index/deleteComment',
            data: 'comment_id='+comment_id,
            success: function(data, status, xhr) {
                console.log ('data:' +data);
                if(/^[0-9]+$/.test(data)) {
                    //success
                    $('.comment-wrapper[data-comment=' +comment_id+']').slideUp(200, function(){
                        $(this).remove();
                    });
                }
                else {
                    //failure
                    alert('Cannot delete your comment');
                }
            }
        });
    }
}
function deleteStatus(status_id)
{
    if(confirm('Do you want to delete your status?'))
    {
        //Ajax - jquery
        $.ajax({
            type:'POST',
            url: 'index.php?rt=index/deleteStatus',
            data: 'status_id='+status_id,
            success: function(data, status, xhr) {
                console.log ('data:' +data);
                if(/^[0-9]+$/.test(data)) {
                    //success
                    no_of_comments = $('.feed[data-status=' +status_id+'] .comment-wrapper').size();
                    slideup_time = 200 + (no_of_comments * 100);
                    $('.feed[data-status=' +status_id+']').slideUp(slideup_time, function(){
                        $(this).remove();
                    });
                    $('#profile_header > span').text('Set your Status using the text box below');
                }
                else {
                    //failure
                    alert('Cannot delete your status');
                }
            }
        });
    }
}