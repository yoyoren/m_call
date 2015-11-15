      <div id="turn-page">
        共计 <span id="totalRecords"><?php echo $this->_var['record_count']; ?></span>
        条记录，共有 <span id="totalPages"><?php echo $this->_var['page_count']; ?></span>
        页，当前第 <span id="pageCurrent"><?php echo $this->_var['filter']['page']; ?></span>
        页，每页 <input type='text' size='3' id="pageSize" value="<?php echo $this->_var['filter']['page_size']; ?>" onkeypress="return listTable.changePageSize(event)" style='width:20px' />
        <span id="page-link">
          <a href="javascript:listTable.gotoPageFirst()">第一页</a>
          <a href="javascript:listTable.gotoPagePrev()">上一页</a>
          <a href="javascript:listTable.gotoPageNext()">下一页</a>
          <a href="javascript:listTable.gotoPageLast()">最末页</a>
          <select id="gotoPage" onchange="listTable.gotoPage(this.value)">
            <?php echo $this->smarty_create_pages(array('count'=>$this->_var['page_count'],'page'=>$this->_var['filter']['page'])); ?>
          </select>
        </span>
      </div>
