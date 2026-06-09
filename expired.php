  <?php
  echo "<!DOCTYPE html><html><head><title>expired session</title><style>";
  echo ".Btn {background-color: red ;  color: white;  padding: 10px 10px;  border: none;  cursor: pointer;  width: 100px;  opacity: 0.9;margin: auto;}";
  echo ".Btn:hover {opacity: 1;}";
  echo "</style></head><body><center><h3>Your session has expired please re-login</h3><form action='".$__systemRoot."login.php' target='_top'>";
  echo "<button type='submit' class='Btn'>Login</button></form></center></body></html>";
  ?>
