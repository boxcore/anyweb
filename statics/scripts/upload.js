/*
 * 用法
 <form action="ss">
 <ul id="new">
 <li class="upload"></li>
 
 </ul>
 <span id="up">UP</span>
 <span id="submit">SUBMIT</span>
 <span id="newadd">add</span>
 </form>
 <script type="text/javascript">
 document.domain = "7808.cn";
 $(function() {
 
 
 $("#newadd").click(function() {
 var nw = $("<li/>");
 nw.upload({callback: function(r, formObj, fileObj) {
 //上传后执行的回调
 
 fileObj.after("<img src='" + r.url + "' width='100'>");
 // fileObj.after("上传完成中");
 }, before: function(fileObj, formObj) {
 //上传前callback return false 取消上传
 fileObj.after("上传中");
 }, change: function(fileObj, formObj) {
 //file change时 callback
 formObj.submit();
 },urlparam:{}});
 nw.appendTo($("#new"));
 })
 
 $.uploadFilename('imgFile');
 $.uploadUrl('http://i.7808.cn/upload_descpic');
 
 
 //上传
 $("#up").click(function() {
 $.uplodSubmitAll();
 })
 
 $("#submit").click(function() {
 alert($.uploadStatus())
 })
 
 
 })
 </script>
 */
(function($) {
    var iframeName = "upload-iframe-target-", formName = 'upload-form-', fileId = "upload-file-";
    var __index = 0, __callback = {}, //上传完后回调
            action = '', fileName = 'upload',
            __upload_status = {};//记录上传状态
    var __iframe_status = {};


    function _doc(index) {
        var iframe = document.getElementById(iframeName + index);
        try {
            return  iframe.contentDocument || iframe.contentWindow.document;
        } catch (e) {
            return false;
        }
    }


    function createForm(index, before, change, urlparam) {

        $("<iframe name='" + iframeName + index + "' id='" + iframeName + index + "' style='display:none' onload='$.uploadTargetCallback(" + index + ")'></iframe>").appendTo("body");
        __iframe_status[index] = 0;

        var formHTML = $('<form data-index="' + index + '" action="' + action + '" id="' + formName + index + '" name="' + formName + index + '" encType="multipart/form-data"  method="post" target="' + iframeName + index + '" />');
        var inputHTML = $('<input type="file" id="' + fileId + index + '" name="' + fileName + '"/>')

        formHTML.submit(function() {
            var check = before(inputHTML, formHTML);
            if (check) {
                __upload_status[index] = false;
            } else {
                __upload_status[index] = true;
            }
            return check;
        });
        inputHTML.change(function() {
            change(inputHTML, formHTML);
        });
        formHTML.append(inputHTML);

        for (var i in urlparam) {
            formHTML.append("<input type='hidden' name='" + i + "' value='" + urlparam[i] + "'>")
        }

        return formHTML;
    }

    $.fn.upload = function(option) {
        // option {callback:, before:, change:,urlparam}
        jQuery.extend({callback: function() {
            }, before: function() {
            }, change: function() {
            }, urlparam: {}}, option);

        $(this).each(function() {
            var self = $(this);
            $.upload(self, option.callback, option.before, option.change, option.urlparam)
        })
    }

    /** 文件是否全部上传完成 */
    $.uploadStatus = function() {
        for (var i in __upload_status) {
            if (__upload_status[i] === false) {
                return false;
            }
        }
        return true;
    }

    $.upload = function(obj, callback, before, change, urlparam) {
        var now_index = __index++;
        __callback[now_index] = callback;
        obj.append(createForm(now_index, before, change, urlparam))
    }

    $.uploadSubmitIndex = function(index) {
        $("#" + formName + index).submit();
    }

    /** 全部提交上传 */
    $.uplodSubmitAll = function() {
        var index = __index;
        for (var i = 0; i < index; i++) {
            $.uploadSubmitIndex(i);
        }
    }

    $.uploadTargetCallback = function(index) {

        if (__iframe_status[index] === 0) {
            __iframe_status[index]++;
            //第一次加载
            return false;
        }
        __iframe_status[index]++;


        var cb = __callback[index];
        var doc = _doc(index);
        if (!doc) {
            alert("上传出错");
            return false;
        }
        var str = "";
        var json = {};
        try {
            str = doc.getElementById('packRemoteJsonData').innerHTML;
            json = jQuery.parseJSON(str);
            jQuery.extend(json, {index: index});
            __upload_status[index] = true;
            cb(json, $("#" + formName + index), $("#" + fileId + index))
        } catch (e) {
            alert("文件上传失败");
        }


    }

    $.uploadFilename = function(name) {
        fileName = name
    }

    $.uploadUrl = function(url) {
        action = url;
    }


    $.uploadTest = function(v) {
        var html = "";
        for (var i in v) {
            html = i + "=>" + v[i] + "\r\n";
        }
        alert(html);
    }
})(jQuery)



