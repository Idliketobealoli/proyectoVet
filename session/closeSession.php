<?php

require_once "../utils/utils.php";
require_once "session.php";

exitIfFailedSession();

closeSession();

redirect("login.php?closedSession");