$("#file").change(
    upload
);
function setStatus(){
    var count=0;
    var t = setInterval(() => {
        var text="Uploading";
        for (let index = 0; index < count; index++) {
            text=text+".";
        }
        $('#status').html(text);
        if(count==5) count=0;
        count++;
    }, 300);
    return t;
}
function upload(){
    var files = $('#file').prop('files');
    if(files.length==0) return;
    if(files[0].size>50417545){
        alert("file size must < 50M");
        return;
    }
    //var t=setStatus();
    var data = new FormData();
    data.append('data', files[0]);

    $.ajax({
        url: '/index.php',
        type: 'POST',
        data: data,
        cache: false,
        processData: false,
        contentType: false,
        xhr: function(){
            myXhr = $.ajaxSettings.xhr();
            if(myXhr.upload){
            myXhr.upload.addEventListener('progress',function(e) {
                if (e.lengthComputable) {
                var percent = Math.floor(e.loaded/e.total*100);
                if(percent <= 100) {
                    $("#status").html('upload: '+percent+'%');
                }
                if(percent >= 100) {
                    $("#status").html('success');
                }
                }
            }, false);
            }
            return myXhr;
        },
        complete:function(data,status){
            //clearInterval(t);
            $('#status').html(status);
            if(status=="success"){
                var url=data.responseText;
                var content=$('#content').val();
                var newline=content+"\n\n"+url;
                $('#content').val(newline);
            }
            
        } 
    });
}