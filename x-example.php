<?php
require_once __DIR__ . '/config/constant.php';


?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta
      name="viewport"
      content="width=device-width,initial-scale=1,user-scalable=no"
    />
    <title>Choices</title>


     <!-- Ignore these -->
     <link rel="stylesheet" href="<?= PLUGIN_PATH ?>choices/assets/styles/base.min.css" />
    <!-- End ignore these -->

    <!-- Optional includes -->
    <script src="https://cdn.polyfill.io/v3/polyfill.min.js?features=Array.from%2Ces5%2Ces6%2CSymbol%2CSymbol.iterator%2CDOMTokenList%2CObject.assign%2CCustomEvent%2CElement.prototype.classList%2CElement.prototype.closest%2CElement.prototype.dataset%2CArray.prototype.find%2CArray.prototype.includes%2Cfetch"></script>
    <!-- End optional includes -->

    <!-- Choices includes -->
    <link rel="stylesheet" href="<?= PLUGIN_PATH ?>choices/assets/styles/choices.min.css" />
    <script src="<?= PLUGIN_PATH ?>choices/assets/scripts/choices.min.js"></script>

  </head>

  <body>
    <div class="container">
      <div class="section">
  
        <label for="choices-multiple-remove-button">With remove button</label>
        <select
          class="form-control"
          name="choices-multiple-remove-button"
          id="choices-multiple-remove-button"
          placeholder="This is a placeholder"
          multiple
        >
          <option value="Choice 1" selected>Choice 1</option>
          <option value="Choice 2">Choice 2</option>
          <option value="Choice 3">Choice 3</option>
          <option value="Choice 4">Choice 4</option>
        </select>
      </div>
    </div>
    <script>
      document.addEventListener("DOMContentLoaded", function () {

        var multipleCancelButton = new Choices(
          "#choices-multiple-remove-button",
          {
            allowHTML: true,
            removeItemButton: true,
          }
        );

      });
    </script>

  </body>
</html>
