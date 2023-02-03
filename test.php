<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>

    <input type="text" id="inp">
    <button id="inp-btn" onclick='setAbsent("inp", this.id)'>X</button>

    <script>
    const setAbsent = (i, btnId) => {
        let input = document.getElementById(i);
        let btn = document.getElementById(btnId);

        if (input.disabled) {
            // if field is disbaled 
            input.disabled = false;
            input.value = "";
            btn.innerText = 'X';
        }else{
            // if field is enabled 
            input.disabled = true;
            input.value = "X";
            btn.innerText = 'A';
        }

            
            // console.log(input.getAttribute('disabled'));
        // if (input.disbaled == true) {
        //     input.value = "";
        //     input.disabled = false;

        //     btn.innerText = 'X';
        // }

    }
    </script>
</body>

</html>