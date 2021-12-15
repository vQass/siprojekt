<?php

session_start();
require_once("paths.php");
require_once("$pSharedFunctions");
if (!isset($_SESSION['user_permission']) && $_SESSION['user_permission'] != "admin") {
  
  header("Location: $pHome");
  exit();
  
}
echo "<a href='menu.php' style='text-decoration: none; color: white;'>
<h3 style='border: 3px dotted white; width: 127px;text-align: center;'> ←Powrót</h3></a>";
if (isset($_SESSION['user_permission']) && $_SESSION['user_permission'] == "admin") {
  try {
    require_once "$pDbConnection";
  } catch (Exception $e) {
    $_SESSION['general_message'] = ErrorMessageGenerator("Błąd podczas łączenia z bazą danych");
    $_SESSION['general_message'] .= ErrorMessageGenerator($e);
    header("Location: menu.php");
  }

  try {
    // Bierzemy id edytowanego pola menu z posta i wyszukujemy go w bazie danych
    if (isset($_POST['edit'])) {
      $id = $_POST['edit'];
      $sth = $dbh->query("SELECT name, category, price, photo, description FROM menu WHERE id = $id");

      $temp = $sth->fetch();
      $_SESSION['eu_id'] = $id;
      $_SESSION['eu_nameMenu'] = $temp['name'];
      $_SESSION['eu_category'] = $temp['category'];
      $_SESSION['eu_price'] = $temp['price'];
      $_SESSION['eu_photo'] = $temp['photo'];
      $_SESSION['eu_description'] = $temp['description'];
    }
  } catch (PDOException $e) {
    $_SESSION['general_message'] = ErrorMessageGenerator("Błąd podczas pobierania informacji o użytkowniku");
    $_SESSION['general_message'] .= ErrorMessageGenerator($e);
  }


  // do formularza edycji, zostawiamy w session ponieważ jeśli dane bedą nieprawidłowe to załadujemy je ponownie do formularza
  if (isset($_SESSION['eu_nameMenu'])) {
    $name = $_SESSION['eu_nameMenu'];
  } else {
    $name = "";
  }
  if (isset($_SESSION['eu_price'])) {
    $price = $_SESSION['eu_price'];
  } else {
    $price = "";
  }
  if (isset($_SESSION['eu_photo'])) {
    $photo = $_SESSION['eu_photo'];
  } else {
    $photo = "";
  }
  if (isset($_SESSION['eu_description'])) {
    $description = $_SESSION['eu_description'];
  } else {
    $description = "";
  }

  $pizza = "";
  $beer = "";
  $default = "";
  if (isset($_SESSION['eu_category'])) {
    if ($_SESSION['eu_category'] == "Pizza") {
      $pizza = "selected";
    } else if ($_SESSION['eu_category'] == "Piwo") {
      $beer = "selected";
    }
  } else {
    $tmp = "selected";
  }

} else {
  // jeśli nie ma uprawnień administratora
  header("Location: $pUsersList");
}

if (isset($_SESSION['ve_name'])) {
  $veName = $_SESSION['ve_name'];
  unset($_SESSION['ve_name']);
} else $veName = "";

if (isset($_SESSION['ve_price'])) {
  $vePrice = $_SESSION['ve_price'];
  unset($_SESSION['ve_price']);
} else $vePrice = "";

if (isset($_SESSION['ve_photo'])) {
  $vePhoto = $_SESSION['ve_photo'];
  unset($_SESSION['ve_photo']);
} else $vePhoto = "";

?>

<!DOCTYPE HTML>
<html lang="pl">

<head>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

  <style>
    body {
      background-color: black;
      color: white;
    }

    .mb-3 {
      width: 30%;
      margin-left: auto;
      margin-right: auto;
    }

    .absolute {
      width: 80%;
      max-width: 800px;
      position: absolute;
      margin-left: auto;
      margin-right: auto;
      margin-top: 20px;
      left: 0;
      right: 0;
      text-align: center;
    }
  </style>
</head>

<body>

  <div class='absolute'>
    <?php
    if (isset($_SESSION['general_message'])) {
      echo $_SESSION['general_message'];
      unset($_SESSION['general_message']);
    }
    ?>
  </div>
  <div class="mb-3" style="margin-top: 40px;">
    <h1>Edycja Menu:</h1>

    <form action='<?php echo $pMenuEditValidation ?>' method='POST' class='g-3' enctype='multipart/form-data'>
      <div class="col-md-12">
        <label for="inputName" class="form-label">Nazwa</label>
        <input type="text" class="form-control <?php echo $veName ?>" id="Name" name="name" placeholder="Jan" value=<?php echo "'$name'" ?>>
      </div>

      <div class="col-md-12">
        <label for="inputCategory" class="form-label">Kategoria</label>
        <select class="form-select" id="inputCategory" name="category">
          <option <?php echo $default ?>>Wybierz</option>
          <option value="Pizza" <?php echo $pizza ?>>Pizza</option>
          <option value="Piwo" <?php echo $beer ?>>Piwo</option>
        </select>
      </div>
      <div class="col-md-12">
        <label for="inputPrice" class="form-label">Cena</label>
        <input type="text" class="form-control <?php echo $vePrice ?>" id="Price" name="price" placeholder="30" value=<?php echo "'$price'" ?>>
      </div>

      <div class="col-md-12">
        <label for="inputPhoto" class="form-label">Zdjęcie: <?php echo $photo ?></label>
        <input type="hidden" class="form-control" id="Photo" name="photo" placeholder="30" value=<?php echo "'$photo'" ?>>
        <input type="file" name="fileToUpload" id="fileToUpload" class="form-select <?php echo $vePhoto ?>">
      </div>

      <div class="col-md-12">
        <label for="inputDescription" class="form-label">Opis</label>
        <textarea id="inputDescripiton" name="description" rows="4" cols="50" class="form-select"><?php echo "$description" ?></textarea>
      </div>
      <br />
      <div class="col-12">
        <button class="btn btn-primary" type="submit">Zatwierdź</button>
      </div>
    </form>
  </div>
</body>

</html>