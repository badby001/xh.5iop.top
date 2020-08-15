/** EasyWeb iframe v3.1.8 date:2020-05-04 License By http://easyweb.vip */
/^http(s*):\/\//.test(location.href) || alert('请先部署到 localhost 下再访问');
layui.config({  // common.js是配置layui扩展模块的目录，每个页面都需要引入
    version: '318.1',   // 更新组件缓存，设为true不缓存，也可以设一个固定值
    defaultTheme: 'theme-blue', // 默认主题
    maxTabNum: 15,              // 最大打开的Tab数量
    base: getProjectUrl() + 'assets/module/'
}).extend({
    steps: 'steps/steps',
    notice: 'notice/notice',
    cascader: 'cascader/cascader',
    dropdown: 'dropdown/dropdown',
    fileChoose: 'fileChoose/fileChoose',
    Split: 'Split/Split',
    Cropper: 'Cropper/Cropper',
    tagsInput: 'tagsInput/tagsInput',
    citypicker: 'city-picker/city-picker',
    introJs: 'introJs/introJs',
    zTree: 'zTree/zTree',
    okCountUp: "../okmodules/okCountUp",
    okUtils: "../okmodules/okUtils",
    okFly: "../okmodules/okFly",
    okGVerify: "../okmodules/okGVerify",
    qrcode: "../okmodules/qrcode",
    okQrcode: "../okmodules/okQrcode",
    okAddlink: "../okmodules/okAddlink",
    okLayer: "../okmodules/okLayer",
    okContextMenu: "../okmodules/okContextMenu",
    okCookie: "../okmodules/okCookie",
    okMd5: "../okmodules/okMd5",
    okToastr: "../okmodules/okToastr",
    okBarcode: "../okmodules/okBarcode",
    okNprogress: "../okmodules/okNprogress",
    okSweetAlert2: "../okmodules/okSweetAlert2",
    okHoliday: "../okmodules/okHoliday",
    okLayx: "../okmodules/okLayx",
    eleTree: "../okmodules/eleTree",
    layarea: "../okmodules/layarea",
}).use(['layer', 'admin'], function () {
    var $ = layui.jquery;
    var layer = layui.layer;
    var admin = layui.admin;

});

/** 获取当前项目的根路径，通过获取layui.js全路径截取assets之前的地址 */
function getProjectUrl() {
    var layuiDir = layui.cache.dir;
    if (!layuiDir) {
        var js = document.scripts, last = js.length - 1, src;
        for (var i = last; i > 0; i--) {
            if (js[i].readyState === 'interactive') {
                src = js[i].src;
                break;
            }
        }
        var jsPath = src || js[last].src;
        layuiDir = jsPath.substring(0, jsPath.lastIndexOf('/') + 1);
    }
    return layuiDir.substring(0, layuiDir.indexOf('assets'));
}
