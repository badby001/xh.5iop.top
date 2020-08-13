<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    @include('.sys.public.css')
    @include('.sys.public.js')
</head>
<body onscroll="layui.admin.hideFixedEl();" class="page-no-scroll">
<!-- 小球样式 -->
<div class="page-loading">
    <div class="ball-loader">
        <span></span><span></span><span></span><span></span>
    </div>
</div>
<!-- 正文开始 -->
<div class="layui-fluid">
    <div class="layui-card">
        <div class="layui-card-body">
            <!-- 表格工具栏 -->
            <form class="layui-form toolbar table-tool-mini">
                <div class="layui-form-item">
                    <div class="layui-inline">
                        <label class="layui-form-label">选择状态</label>
                        <div class="layui-input-inline">
                            <select name="state" lay-verify="">
                                <option value="" selected>请选择状态</option>
                                <option value="1">待认证</option>
                                <option value="2">已认证</option>
                                <option value="-1">认证失败</option>
                            </select>
                        </div>
                    </div>
                    <div class="layui-inline">
                        <label class="layui-form-label">搜索</label>
                        <div class="layui-input-inline">
                            <input type="text" class="layui-input" placeholder="请输入关键字 | 旅行社名称/姓名/地址" autocomplete="off" name="key">
                        </div>
                    </div>
                    <div class="layui-inline" style="padding-right: 110px;">
                        <button class="layui-btn icon-btn" lay-filter="search" lay-submit>
                            <i class="layui-icon">&#xe615;</i>搜索
                        </button>
                    </div>
                </div>
            </form>
            <!-- 数据表格 -->
            <table id="tableId" lay-filter="tableFilter"></table>
        </div>
    </div>
</div>
<!--js逻辑-->
<script>
    var json;
    layui.use(["admin","element", "jquery", "table", "form",  "okLayer"], function () {
	    let admin = layui.admin;
        let table = layui.table;
        let form = layui.form;
        let okLayer = layui.okLayer;
        let $ = layui.jquery;
        let checkTable = table.render({
            elem: '#tableId',
            url: '{{url('/sys/pages/system/attestationRead')}}',
            limit: '{!! frame()['limit'] !!}',
            limits: [{!! frame()['limits'] !!}],
            title: '认证列表_{{getTime(3)}}',
            page: true,
            size: "sm",
            cols:
                [[
                    {field: "attestation_state_show", title: "认证状态", align: "center", width: 100, sort: true, fixed: "left"},
                    {field: "adm_code", title: "编号", width: 100},
                    {field: "attestation_tourist_agency", title: "旅行社名称", width: 220},
                    {field: "name", title: "姓名", width: 100},
                    {field: "sex", title: "性别", width: 60},
                    {field: "attestation_area", title: "省市区", width: 160},
                    {field: "attestation_address", title: "地址", width: 180},
                    {field: "attestation_business_license_img", title: "营业执照", width: 120},
                    {
                        title: "操作", width: 100, align: "center", fixed: "right", templet: function (d) {
                            var check = '';
                            if (d.attestation_state == 1) {
                                check = "@if(hasPower(158))<a href=\"javascript:\" title=\"审核\" lay-event=\"check\"><i class=\"ok-icon\">&#xe769;</i></a>@endif";
                            }
                            return check;
                        }
                    }
                ]],
            done: function (res, curr, count) {
                admin.removeLoading();
                //console.info(res, curr, count);
            }
        });
        form.on("submit(search)", function (data) {
            checkTable.reload({
                where: data.field,
                page: {curr: 1}
            });
            return false;
        });


        table.on("tool(tableFilter)", function (obj) {
            let data = obj.data;
            switch (obj.event) {
                case "check":
                    check(data);
                    break;
                case "":
                    break;
            }
        });


        function check(data) {
            json = JSON.stringify(data);
            okLayer.open("同行认证", "attestation/" + data.adm_code, "90%", "90%", null, function () {
                checkTable.reload();
            })
        }
    })
</script>
</body>
</html>
