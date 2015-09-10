debug = true;
// var defaults = { fID: '', title: '', link_url: '', cID: '', description: '', sort_order: '', image_url: '', image_link: '', image_link_text: '', image_thumbnail_width:'', image_bg_color:'#fff'};
// $.extend(defaults, {fID: file.fID, title: file.title, description: file.description, sort_order: '', image_url: file.urlInline, image_link: file.image_link, image_link_text: file.image_link_text, image_thumbnail_width: file.image_thumbnail_width, image_bg_color:file.image_bg_color});

// var _templateSlide = _.template($('#SlideTemplate').html());
// $('.foo').append(_templateSlide(defaults));


function l() {
    if(debug==true) {
        for (var i=0; i < arguments.length; i++) {
            console.log(arguments[i]);
        }
    } 
}

