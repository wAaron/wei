<?php
/**
 * default
 *
 * Copyright (c) 2008-2010 Twin Huang. All rights reserved.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2011-01-31 14:20:01
 */
?>

<table id="<?php echo $option['id'] ?>"></table>
<div id="<?php echo $option['pager'] ?>"></div>
<script type="text/javascript">
jQuery(function($){
    var jqGrid = <?php echo $jqGridJson?>;
    // todo 如何在php代码中表示js function
    jqGrid.ondblClickRow = function(){};
    $('#<?php echo $option['id'] ?>')
        .jqGrid(jqGrid)
        .jqGrid('navGrid', jqGrid.pager,{
            add : false,
            edit : false,
            del : false,
            search : false
        });

    // 样式调整
    var gboxId = '#gbox_<?php echo $option['id'] ?>';
    $(gboxId).width($(gboxId).width() - 2).addClass('ui-state-default');
    $('table.ui-jqgrid-htable tr.ui-jqgrid-labels th:last').css('border-right', 'none');
});
</script>