        <div class="masthead">
          <ul class="nav nav-pills pull-right">
            <li><a href="create.php">添加项目</a></li>
            <li class="dropdown">
              <a class="dropdown-toggle" data-toggle="dropdown" href="#"><?php echo $_SESSION['nickname'] ?><b class="caret"></b></a>
              <ul class="dropdown-menu">
                <li><a tabindex="-1" href="../user/invite_code.php">生成邀请码</a></li>
                <li><a tabindex="-1" href="#">修改密码</a></li>
                <li><a tabindex="-1" href="../user/logout.php">退出</a></li>
              </ul>
            </li>
          </ul>
          <h3 class="muted">XSS Platform</h3>
        </div>
