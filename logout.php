<?php

  session_destroy();
  session_start();
  $_SESSION['trtv'] = '';

  header('Location: ./home');

  ?>