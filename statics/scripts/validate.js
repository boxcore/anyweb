/**
 * 错误验证
 * $.validateBind(rules) 绑定验证
 * $.validateCheck(rules) 验证 返回true 验证通过 false 失败
 * $.validateData(key) 获取验证后的数据
 * 
 * rules = {
 *  "字段" : [{regex:/^sss$/,msg:"错误信息",place:function(obj, msg, status){ obj input对象 msg 提示信息 status 状态  'success' 成功  'error' 失败 }}, {regex:function(val,obj){ val 位值 obj 表单jquery 对象},msg:"错误信息"}]
 * }
 */

(function($) {
    //错误信息
    var error = {
        data: [],
        add: function(obj, msg) {
            var name = obj.attr('name');
            for (var i in this.data) {
                if (this.data[i]['name'] && this.data[i]['name'] === name) {
                    this.data[i] = {name: name, msg: msg};
                    return;
                }
            }
            this.data.push({name: name, msg: msg});
        },
        length: function() {
            return this.data.length;
        },
        isEmpty: function() {
            //错误信息是否为空
            return this.data.length > 0 ? false : true;
        },
        empty: function() {
            this.data = [];
        },
        remove: function(obj) {
            var name = obj.attr('name');
            var tmp = new Array();
            for (var i in this.data) {
                if (this.data[i]['name'] !== name) {
                    tmp.push(this.data[i]);
                }
            }
            this.data = tmp;
        }
    }
    var scope = window.document;
    //验证过后的数据
    var validateData = {};


    function isArray(obj) {
        return Object.prototype.toString.call(obj) === '[object Array]';
    }

    //获取值
    function iObj(name) {
        var o = $("input[name='" + name + "']", scope);
        return o.length === 0 ? false : o;
    }
    function sObj(name) {
        var o = $("select[name='" + name + "']", scope);
        return o.length === 0 ? false : o;
    }
    function eObj(name) {
        if (typeof (KindEditor) !== 'undefined') {
            KindEditor.sync("textarea[name='" + name + "']");
        }
        var o = $("textarea[name='" + name + "']", scope);
        return o.length === 0 ? false : o;
    }
    //end 获取值

    function getObj(name) {
        var obj = iObj(name) || sObj(name) || eObj(name);
        if (obj) {
            return obj;
        } else {
            alert(name + " field not exsit");
        }
    }


    //错误放置位置位子
    function errorPlace(obj, msg) {
        obj.addClass('validate-error-input')
        if (obj.next('span.validate-msg').length > 0) {
            obj.next('span.validate-msg').removeClass('validate-success').addClass('validate-error').html(msg)
        } else {
            obj.after('<span class="validate-msg validate-error">' + msg + '</span>')
        }
    }
//验证通过信息放置位子
    function successPlace(obj, msg) {
        obj.removeClass('validate-error-input');
        if (obj.next('span.validate-msg').length > 0) {
            obj.next('span.validate-msg').removeClass('validate-error').addClass('validate-success').html('');
        } else {
            obj.after('<span class="validate-msg validate-success">' + (msg || "") + '</span>');
        }
    }
//验证单个input
    function validateField(obj, validate) {
        var value = obj.val();
        var name = obj.attr('name');
        var rule;
        var check = false;
        var rules = validate[name]['rule'];
        if (!isArray (rules)) {
            rules = [rules];
        }
        for (var ruleIndex in rules) {
            rule = rules[ruleIndex];
            rule.lastIndex = 0;
            //验证
            if (typeof (rule.regex) === 'function') {
                //函数
                check = rule.regex(value, obj);
            } else {
                //正则
                check = rule.regex.test(value);

            }
            //错误存放位置
            if (check) {
                error.remove(obj);
                //获取数据
                validateData[name] = value;

                var placeFn = validate[name]['place'];
                if (typeof (placeFn) == 'function') {
                    placeFn(obj, rule.msg, 'success');
                } else {
                    successPlace(obj);
                }

            } else {
                error.add(obj, rule.msg);
                var placeFn = validate[name]['place'];
                if (typeof (placeFn) == 'function') {
                    placeFn(obj, rule.msg, 'error');
                } else {
                    errorPlace(obj, rule.msg);
                }
                //有错误不验证后面的规则
                //  obj.focus();
                break;
            }
        }
        //错误放的位置
    }
//验证form
    var checkField = function(validate) {
        for (var name in validate) {
            (function(name) {
                var obj = getObj(name);
                var tagName = obj.get(0).tagName.toLowerCase();
                obj.blur(function() {
                    validateField(obj, validate)
                })
                if (tagName == 'input' || tagName == 'textarea') {
                    obj.keyup(function() {
                        validateField(obj, validate)
                    })
                }
            })(name)
        }
    }

    $.validateCheck = function(validate) {
        //重置数据和错误信息
        validateData = {};
        error.empty();
        for (var name in validate) {
            (function(name) {
                var obj = iObj(name) || sObj(name) || eObj(name);
                validateField(obj, validate)
            })(name)
        }
        return error.isEmpty();
    }

    $.validateData = function(key) {
        if (typeof (key) === 'undefined') {
            return validateData;
        } else {
            return validateData[key];
        }
    }

    $.validateBind = function(validate) {
        $(function() {
            checkField(validate);
        })
    }

    $.validateScope = function(obj) {
        scope = obj;
    }

    $.validateError = error;

})(jQuery)

