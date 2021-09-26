<!doctype html>
<html lang="ru">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <script src="skmeans.js"></script>
    <title>результат поиска</title>
</head>

<body class=" bg-dark text-white">
    <div class='container p-2'>
        <input type="button" class="btn btn-info btn-lg btn-block" value='на главную' onclick="javascript:window.location ='https://ilgiz.h1n.ru/index.php'" />
        <select id="my_select" name="my_select" class="form-control" onchange="sel()">
            <!-- onchange лучше чем онклик работает на сенсорных устройствах-->
            <option value="0">без сортировки </option>
            <option value="1">сортировка по расстоянию</option>
            <option value="2">ближние от РП</option>
            <option value="3">ближние от ПС</option>
            <option value="4">ближние от ТП</option>
            <option value="5">ближние к замеру</option>
        </select>
    </div>
    <?php
    define('DB_HOST', 'localhost');
    define('DB_USER', 'kolegi');
    define('DB_PASS', '6I7z3I6k');
    define('DB_NAME', 'pov');

    if (!@mysql_connect(DB_HOST, DB_USER, DB_PASS)) {
        exit('Cannot connect to server');
    }
    if (!mysql_select_db(DB_NAME)) {
        exit('Cannot select database');
    }

    mysql_query('SET NAMES utf8');

    function search($query)
    {
        $query = trim($query);
        $query = mysql_real_escape_string($query);
        $query = htmlspecialchars($query);

        if (!empty($query)) {
            if (strlen($query) < 3) {
                $text = '<p>Слишком короткий поисковый запрос.</p>';
            } else if (strlen($query) > 128) {
                $text = '<p>Слишком длинный поисковый запрос.</p>';
            } else {
                // var_dump($query);
                $q = "SELECT * FROM `TABLE 28` WHERE `COL 1` LIKE '%$query%'"; //1 место где изменять номер таблицы

                $result = mysql_query($q);
                //   var_dump($result);
                if (mysql_affected_rows() > 0) {
                    $row = mysql_fetch_assoc($result);
                    $num = mysql_num_rows($result);

                    $text = "<div id='povrejdenia' class='container p-2'>
                <button type='button' class='btn btn-success '>По запросу <"
                        . $query . '> найдено совпадений: '
                        . "<span class='badge badge-light '>"
                        . $num . "</span>"
                        . "</button><div>";

                    do {
                        // Делаем запрос, получающий ссылки на статьи
                        $q1 = "SELECT * FROM `TABLE 28` WHERE `id` = '$row[id]'"; //2 место где изменять номер таблицы
                        $result1 = mysql_query($q1);

                        if (mysql_affected_rows() > 0) {
                            $row1 = mysql_fetch_assoc($result1);
                        }
                        //moi

                        //moi
                        $text .= "<div  class='priv'>
            <div id='kl-name' >
                                " . $row['COL 1'] . "</div>
                     <p id='kl-priv' class='bg-success'>" . $row['COL 2'] . "</p>
           </div>";
                    } while ($row = mysql_fetch_assoc($result));
                } else {
                    $text = '<p>По вашему запросу ничего не найдено.</p>';
                }
            }
        } else {
            $text = '<p>Задан пустой поисковый запрос.</p>';
        }

        return $text;
    }
    function search_new($query)
    {
        $query = trim($query);
        $query = mysql_real_escape_string($query);
        $query = htmlspecialchars($query);

        if (!empty($query)) {
            if (strlen($query) < 3) {
                $text = '<p>Слишком короткий поисковый запрос.</p>';
            } else if (strlen($query) > 128) {
                $text = '<p>Слишком длинный поисковый запрос.</p>';
            } else {
                // var_dump($query);
                $q = "SELECT * FROM `povkl` WHERE `name` LIKE '%$query%'";

                $result = mysql_query($q);
                //   var_dump($result);
                if (mysql_affected_rows() > 0) {
                    $row = mysql_fetch_assoc($result);
                    $num = mysql_num_rows($result);

                    $text = "<div class='container p-2'>
                    <button type='button' class='btn btn-success '>По запросу <"
                        . $query . '> найдено совпадений в новой базе: '
                        . "<span class='badge badge-light '>"
                        . $num . "</span>"
                        . "</button><div>";

                    do {
                        // Делаем запрос, получающий ссылки на статьи
                        $q1 = "SELECT * FROM `povkl` WHERE `id` = '$row[id]'";
                        $result1 = mysql_query($q1);

                        if (mysql_affected_rows() > 0) {
                            $row1 = mysql_fetch_assoc($result1);
                        }
                        //moi
                        //moi проверяет есть ли картинка в ответе из базы
                        // var_dump($row1['foto1']); 
                        if (!$row1['foto1']) {
                            $text_foto1 = '<tr><td></td><td>нет фото';
                        } else {
                            $text_foto1 = '<tr><td></td><td><a href="' . $row1['foto1'] . '"><img src="' . $row1['foto1'] . '"width="200"  height="200"> </a>';
                        }

                        if (!$row1['foto2']) {
                            $text_foto2 = '<tr><td></td><td>нет фото';
                        } else {
                            $text_foto2 = '<tr><td></td><td><a href="' . $row1['foto2'] . '"><img src="' . $row1['foto2'] . '"width="200"  height="200"></a>';
                        }

                        if (!$row1['foto3']) {
                            $text_foto3 = '<tr><td></td><td>нет фото';
                        } else {
                            $text_foto3 = '<tr><td></td><td><a href="' . $row1['foto3'] . '"><img src="' . $row1['foto3'] . '"width="200"  height="200"></a>';
                        }
                        //moi конец фото проверки
                        // проверяет есть ли координаты в ответе из базы
                        // var_dump($row['gps']); 
                        if (!$row['gps']) {
                            $text_gps = '<tr><td></td><td>нет координат';
                        } else {
                            $text_gps = '<tr><td>gps</td><td><a href="https://maps.google.com/?hl=ru&q=' . $row['gps'] . '">место </a>';
                        }
                        //end проверяет есть ли координаты в ответе из базы
                        //moi
                        $text .= "
      <div class='table-responsive alert alert-success'><table class='table table-bordered '>
       
       
       
             <tr><td>имя</td><td> " . $row['name']
                            . '<tr><td>дата</td><td> ' . $row['date'] .
                            '<tr><td>замер</td><td>  ' . $row['zamer'] .
                            '<tr><td>откуда замер</td><td>  ' . $row['otkuda'] .
                            '<tr><td> привязка </td><td>  ' . $row['priv'] .
                            '<tr><td> вся длинна </td><td>  ' . $row['dlinna'] .
                            // '<tr><td>gps</td><td><a href="https://maps.google.com/?hl=ru&q='.$row['gps'].'">место </a>'.
                            $text_gps .
                            '<tr><td> кто </td><td>  ' . $row['kto'] .
                            $text_foto1 . $text_foto2 . $text_foto3 .
                            /*'<tr><td></td><td><a href="'.$row1['foto1'].'">фото1 </a>'.
             '<tr><td></td><td><a href="'.$row1['foto2'].'">фото2 </a>'.
             '<tr><td></td><td><a href="'.$row1['foto3'].'">фото3 </a>*/
                            '</td></tr>
      </table></div><br>';
                    } while ($row = mysql_fetch_assoc($result));
                } else {
                    $text = '<p>По вашему запросу в новой базе ничего не найдено.</p>';
                }
            }
        } else {
            $text = '<p>Задан пустой поисковый запрос.</p>';
        }

        return $text;
    }
        // info_user
    function record_user_info($query, $brows, $screen, $plug, $date, $position)
    {
        if (!empty($query)) {
            $link = mysqli_connect(DB_HOST, DB_USER, DB_PASS, 'pov')
                or die("Ошибка " . mysqli_error($link));
            // создание строки запроса
            $query_db = "INSERT INTO who_db VALUES(NULL,'$query','$date','$brows','$screen','$plug','$position','NULL')";
            // выполняем запрос
            $result = mysqli_query($link, $query_db) or die("Ошибка " . mysqli_error($link));
            mysqli_close($link);
        }
    }
    // end info_user
    if (!empty($_POST['query'])) {
        $search_result = search($_POST['query']);
        $search_result_new = search_new($_POST['query']);
        echo $search_result;
        echo $search_result_new;
    }
        if (!empty($_POST['brows'])) {
        // $tt = $_POST['brows'];
        // var_dump($tt);
        record_user_info($_POST['query'], $_POST['brows'], $_POST['screen'], $_POST['plug'],$_POST['date'], $_POST['position']);
    }
    ?>
    <!--это анучат для графиков-->
    <div id="container-graf" class="container-graf"></div>
    <div id="statistic" class="statistic"></div>
    <div id="container-graf1" class="container-graf1"></div>
    <script src="https://cdn.anychart.com/js/latest/anychart-bundle.min.js"></script>
    <script>
        // AnyChart code here
        function graf(x, x1, x2, x3, x4, s, s1, s2, s3, s4, otName) {
            // Удаление результатов предыдущего поиска 
            var node = document.getElementById("container-graf");
            while (node.firstChild) {
                node.removeChild(node.firstChild);
            }
            vsego = x + x1 + x2 + x3 + x4;
            var chart = anychart.column([
                [s, x],
                [s1, x1],
                [s2, x2],
                [s3, x3],
                [s4, x4]
            ]);
            // set chart title
            chart.title("количество повреждений от расстояний до места, всего " + vsego + "шт. от " + otName);
            // set chart container and draw
            chart.container("container-graf").draw();
            //});
        }
        //end AnyChart code here
        function graf1(mass2) {
            // Удаление результатов предыдущего поиска 
            var node1 = document.getElementById("container-graf1");
            while (node1.firstChild) {
                node1.removeChild(node1.firstChild);
            }
            anychart.onDocumentReady(function() {

                // create data
                var data = mass2

                // create a chart
                var chart = anychart.line();

                // create an area series and set the data
                var series = chart.line(data);

                // set the chart title
                chart.title("график время/место повреждения КЛ");

                // set the titles of the axes
                chart.xAxis().title("год");
                chart.yAxis().title("замер, м");

                // set the container id
                chart.container("container-graf1");

                // initiate drawing the chart
                chart.draw();
            });
        }
        <!--конец анучат-->
    </script>
</body>
<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
<script>
    var massiv = [];

    function searchZamer() {
        massiv = [];
        var elems = document.getElementsByClassName('priv');
        for (var i = 0; i < elems.length; i++) {

            var elem1 = elems[i].children[0].innerText
            var elem2 = elems[i].children[1].innerText
            console.log(elem1);
            console.log(elem2);
            var str = elem2;
            var result = str.match(/ \d+ м от /gi) //поиск замера из текста пробел м
            if (result === null) {
                result = str.match(/ \d+м от /gi) //поиск замера из текста без пробел м
            }
            if (result === null) {
                result = str.match(/ \d+м. от /gi) //поиск замера из текста без пробел м
            }
            if (result === null) {
                result = str.match(/ \d+ м. от /gi) //поиск замера из текста без пробел м
            }
            if (result !== null) {
                result[0] = result[0].replace(/[^-0-9]/gim, ''); //удаление лишних символов из замера
            } else {
                result = []
                result[0] = 0
            }
            console.log(result[0]);
            resultDate1 = searchDate(str)
            //конец поиск даты
            console.log(resultDate1[0]);
            //начало преобразовать дату в милисекунды с 1970г
            let dateRazbor = []
            dateRazbor = resultDate1[0].split('.')
            // console.log(dateRazbor)
            let msUTC
            msUTC = Date.parse(dateRazbor[2] + "-" + dateRazbor[1] + "-" + dateRazbor[0])
            // console.log(msUTC)
            //конец преобразовать дату в милисекунды
            var povr = {};
            povr.name = elem1;
            povr.priv = elem2;
            povr.zamer = result[0];
            povr.date = resultDate1[0];
            povr.dateSec = msUTC;
            massiv.push(povr);

        }
        massiv.sort(function(a, b) { //сортировка масива по наименьшему
            return a.zamer - b.zamer
        })
        console.log(massiv);
        let massivDateSort = [];
        massivDateSort = massiv.slice().sort(function(a, b) { //сортировка масива по наименьшему чтоб не изменять исходный массив надо slice 
            return a.dateSec - b.dateSec
        })


        var mass2 = dateGraf(massivDateSort)
        console.log(mass2)
        // console.log(massivDateSort);
        graf1(mass2);
    }

    function searchZamerPn() {
        massiv = [];
        var elems = document.getElementsByClassName('priv');
        for (var i = 0; i < elems.length; i++) {

            var elem1 = elems[i].children[0].innerText
            var elem2 = elems[i].children[1].innerText
            console.log(elem1);
            console.log(elem2);
            var str = elem2;
            var result = []
            result[0] = otRP1(str).result
            console.log(result[0]);
            resultDate1 = searchDate(str)
            //конец поиск даты
            console.log(resultDate1[0]);
            //начало преобразовать дату в милисекунды с 1970г
            let dateRazbor = []
            dateRazbor = resultDate1[0].split('.')
            // console.log(dateRazbor)
            let msUTC
            msUTC = Date.parse(dateRazbor[2] + "-" + dateRazbor[1] + "-" + dateRazbor[0])
            // console.log(msUTC)
            //конец преобразовать дату в милисекунды
            var povr = {};
            povr.name = elem1;
            povr.priv = elem2;
            povr.zamer = result[0];
            povr.date = resultDate1[0];
            povr.dateSec = msUTC;
            povr.opis = otRP1(str).opis
            massiv.push(povr);
        }
        massiv.sort(function(a, b) { //сортировка масива по наименьшему
            return a.zamer - b.zamer
        })
        console.log(massiv);
        let massivDateSort = [];
        massivDateSort = massiv.slice().sort(function(a, b) { //сортировка масива по наименьшему чтоб не изменять исходный массив надо slice 
            return a.dateSec - b.dateSec
        })


        var mass2 = dateGraf(massivDateSort)
        console.log(mass2)
        // console.log(massivDateSort);
        graf1(mass2);
        //end 21.04
        var sumElem = 0;
        var zamerSort = [];
        for (var i = 0; i < massiv.length; i++) {
            console.log(massiv[i].zamer);
            if (massiv[i].zamer !== 10000) {
                var k = i;
                sumElem = sumElem + Number(massiv[i].zamer);
                zamerSort.push(+massiv[i].zamer)
            }
        }
        if (zamerSort.length >= 2) {
            console.log("сумма замеров " + sumElem);
            console.log("массив отсортированных замеров " + zamerSort)
            console.log("min " + massiv[0].zamer + "  max " + massiv[k].zamer); //min and max
            var interval = massiv[k].zamer - massiv[0].zamer;
            console.log("interval" + interval);
            var razrad = interval / 5;
            console.log("razrad " + razrad);
            var num = 0,
                num2 = 0,
                num3 = 0,
                num4 = 0,
                num5 = 0,
                s = '',
                s1 = '',
                s2 = '',
                s3 = '',
                s4 = '';
            // var predel = (+massiv[0].zamer + razrad);
            //к-средних начало
            const data = zamerSort;
            console.log(data);
            const res = skmeans(data, 5, [zamerSort[0], (+zamerSort[0] + 1.25 * razrad), (+zamerSort[0] + 2.5 * razrad), (+zamerSort[0] + 3.75 * razrad), (+zamerSort[0] + 5 * razrad)]);

            console.log(res);
            console.log(res.idxs);
            const id = res.idxs;

            function sovpad(k, massiv) {
                let n = 0,
                    viborka = [];

                for (i = 0; i < massiv.length; i++) {
                    if (id[i] === k) {
                        viborka.push(data[i])
                        console.log(data[i])
                        n++;
                    }

                }
                console.log("совпадений " + k + " количество " + n + " viborka ot" + viborka[0] + " do" + viborka[(viborka.length - 1)]);
                return {
                    viborka
                }
            }

            sovpad(0, id);
            sovpad(1, id);
            sovpad(2, id);
            sovpad(3, id);
            sovpad(4, id);

            let viborka1 = sovpad(0, id).viborka
            s = "от " + Math.round(+viborka1[0]) + "м до " + Math.round(+viborka1[(viborka1.length - 1)]) + "м";
            num = viborka1.length;
            console.log(s);

            let viborka2 = sovpad(1, id).viborka
            s1 = "от " + Math.round(+viborka2[0]) + "м до " + Math.round(+viborka2[(viborka2.length - 1)]) + "м";
            num2 = viborka2.length;
            console.log(s1);

            let viborka3 = sovpad(2, id).viborka
            s2 = "от " + Math.round(+viborka3[0]) + "м до " + Math.round(+viborka3[(viborka3.length - 1)]) + "м";
            num3 = viborka3.length;
            console.log(s2);

            let viborka4 = sovpad(3, id).viborka
            s3 = "от " + Math.round(+viborka4[0]) + "м до " + Math.round(+viborka4[(viborka4.length - 1)]) + "м";
            num4 = viborka4.length;
            console.log(s3);

            let viborka5 = sovpad(4, id).viborka
            s4 = "от " + Math.round(+viborka5[0]) + "м до " + Math.round(+viborka5[(viborka5.length - 1)]) + "м";
            num5 = viborka5.length;
            console.log(s4);
            //к-средних конец

            console.log(num, num2, num3, num4, num5);
            var elemNum = num + num2 + num3 + num4 + num5;

            function verayt(num, elemNum) {
                if ((num > 0) && (elemNum > 0)) {
                    num = Number(num)
                    elemNum = Number(elemNum)
                    return Math.round((num / elemNum) * 100)
                } else return 0
            }


            var percent1 = s + ", вероятность следующего повреждения " + verayt(num, elemNum) + " %"
            var percent2 = s1 + ", вероятность следующего повреждения " + verayt(num2, elemNum) + " %"
            var percent3 = s2 + ", вероятность следующего повреждения " + verayt(num3, elemNum) + " %"
            var percent4 = s3 + ", вероятность следующего повреждения " + verayt(num4, elemNum) + " %"
            var percent5 = s4 + ", вероятность следующего повреждения " + verayt(num5, elemNum) + " %"

            viewVer(percent1, percent2, percent3, percent4, percent5)
            //
            graf(num, num2, num3, num4, num5, s, s1, s2, s3, s4, "РП");
        } else {
            $(".container-graf").hide();
            $(".statistic").hide();
        }
    }

    function searchZamerPC() {
        massiv = [];
        var elems = document.getElementsByClassName('priv');
        for (var i = 0; i < elems.length; i++) {

            var elem1 = elems[i].children[0].innerText
            var elem2 = elems[i].children[1].innerText
            console.log(elem1);
            console.log(elem2);
            var str = elem2;
            var result = []
            result[0] = otPC1(str).result
            console.log(result[0]);
            resultDate1 = searchDate(str)
            //конец поиск даты
            console.log(resultDate1[0]);
            //начало преобразовать дату в милисекунды с 1970г
            let dateRazbor = []
            dateRazbor = resultDate1[0].split('.')
            // console.log(dateRazbor)
            let msUTC
            msUTC = Date.parse(dateRazbor[2] + "-" + dateRazbor[1] + "-" + dateRazbor[0])
            // console.log(msUTC)
            //конец преобразовать дату в милисекунды
            var povr = {};
            povr.name = elem1;
            povr.priv = elem2;
            povr.zamer = result[0];
            povr.date = resultDate1[0];
            povr.dateSec = msUTC;
            povr.opis = otPC1(str).opis
            massiv.push(povr);
        }
        massiv.sort(function(a, b) { //сортировка масива по наименьшему
            return a.zamer - b.zamer
        })
        console.log(massiv);
        let massivDateSort = [];
        massivDateSort = massiv.slice().sort(function(a, b) { //сортировка масива по наименьшему чтоб не изменять исходный массив надо slice 
            return a.dateSec - b.dateSec
        })


        var mass2 = dateGraf(massivDateSort)
        console.log(mass2)
        // console.log(massivDateSort);
        graf1(mass2);
        //end 21.04
        var sumElem = 0;
        var zamerSort = [];
        for (var i = 0; i < massiv.length; i++) {
            console.log(massiv[i].zamer);
            if (massiv[i].zamer !== 10000) {
                var k = i;
                sumElem = sumElem + Number(massiv[i].zamer);
                zamerSort.push(+massiv[i].zamer) //важно поставить +
            }
        }
        if (zamerSort.length >= 2) {
            console.log("сумма замеров " + sumElem);
            console.log("массив отсортированных замеров " + zamerSort)
            console.log("min " + massiv[0].zamer + "  max " + massiv[k].zamer); //min and max
            var interval = massiv[k].zamer - massiv[0].zamer;
            console.log(interval);
            var razrad = interval / 5;
            console.log(razrad);
            var num = 0,
                num2 = 0,
                num3 = 0,
                num4 = 0,
                num5 = 0,
                s = '',
                s1 = '',
                s2 = '',
                s3 = '',
                s4 = '';
            //к-средних начало
            const data = zamerSort;
            console.log(data);
            const res = skmeans(data, 5, [zamerSort[0], (+zamerSort[0] + 1.25 * razrad), (+zamerSort[0] + 2.5 * razrad), (+zamerSort[0] + 3.75 * razrad), (+zamerSort[0] + 5 * razrad)]);

            console.log(res);
            console.log(res.idxs);
            const id = res.idxs;

            function sovpad(k, massiv) {
                let n = 0,
                    viborka = [];

                for (i = 0; i < massiv.length; i++) {
                    if (id[i] === k) {
                        viborka.push(data[i])
                        console.log(data[i])
                        n++;
                    }

                }
                console.log("совпадений " + k + " количество " + n + " viborka ot" + viborka[0] + " do" + viborka[(viborka.length - 1)]);
                return {
                    viborka
                }
            }

            sovpad(0, id);
            sovpad(1, id);
            sovpad(2, id);
            sovpad(3, id);
            sovpad(4, id);

            let viborka1 = sovpad(0, id).viborka
            s = "от " + Math.round(+viborka1[0]) + "м до " + Math.round(+viborka1[(viborka1.length - 1)]) + "м";
            num = viborka1.length;
            console.log(s);

            let viborka2 = sovpad(1, id).viborka
            s1 = "от " + Math.round(+viborka2[0]) + "м до " + Math.round(+viborka2[(viborka2.length - 1)]) + "м";
            num2 = viborka2.length;
            console.log(s1);

            let viborka3 = sovpad(2, id).viborka
            s2 = "от " + Math.round(+viborka3[0]) + "м до " + Math.round(+viborka3[(viborka3.length - 1)]) + "м";
            num3 = viborka3.length;
            console.log(s2);

            let viborka4 = sovpad(3, id).viborka
            s3 = "от " + Math.round(+viborka4[0]) + "м до " + Math.round(+viborka4[(viborka4.length - 1)]) + "м";
            num4 = viborka4.length;
            console.log(s3);

            let viborka5 = sovpad(4, id).viborka
            s4 = "от " + Math.round(+viborka5[0]) + "м до " + Math.round(+viborka5[(viborka5.length - 1)]) + "м";
            num5 = viborka5.length;
            console.log(s4);
            //к-средних конец

            console.log(num, num2, num3, num4, num5);
            var elemNum = num + num2 + num3 + num4 + num5;

            function verayt(num, elemNum) {
                if ((num > 0) && (elemNum > 0)) {
                    num = Number(num)
                    elemNum = Number(elemNum)
                    return Math.round((num / elemNum) * 100)
                } else return 0
            }


            var percent1 = s + ", вероятность следующего повреждения " + verayt(num, elemNum) + " %"
            var percent2 = s1 + ", вероятность следующего повреждения " + verayt(num2, elemNum) + " %"
            var percent3 = s2 + ", вероятность следующего повреждения " + verayt(num3, elemNum) + " %"
            var percent4 = s3 + ", вероятность следующего повреждения " + verayt(num4, elemNum) + " %"
            var percent5 = s4 + ", вероятность следующего повреждения " + verayt(num5, elemNum) + " %"

            viewVer(percent1, percent2, percent3, percent4, percent5)
            //
            graf(num, num2, num3, num4, num5, s, s1, s2, s3, s4, "ПС");
        } else {
            $(".container-graf").hide();
            $(".statistic").hide();
        }
    }

    function searchZamerTn() {
        massiv = [];
        var elems = document.getElementsByClassName('priv');
        for (var i = 0; i < elems.length; i++) {

            var elem1 = elems[i].children[0].innerText
            var elem2 = elems[i].children[1].innerText
            console.log(elem1);
            console.log(elem2);

            var str = elem2;
            var result = []
            result[0] = otTP1(str).result
            console.log(result[0]);
            resultDate1 = searchDate(str)
            //конец поиск даты
            console.log(resultDate1[0]);
            //начало преобразовать дату в милисекунды с 1970г
            let dateRazbor = []
            dateRazbor = resultDate1[0].split('.')
            // console.log(dateRazbor)
            let msUTC
            msUTC = Date.parse(dateRazbor[2] + "-" + dateRazbor[1] + "-" + dateRazbor[0])
            // console.log(msUTC)
            //конец преобразовать дату в милисекунды
            var povr = {};
            povr.name = elem1;
            povr.priv = elem2;
            povr.zamer = result[0];
            povr.date = resultDate1[0];
            povr.dateSec = msUTC;
            povr.opis = otTP1(str).opis
            massiv.push(povr);
        }
        massiv.sort(function(a, b) { //сортировка масива по наименьшему
            return a.zamer - b.zamer
        })
        console.log(massiv);
        let massivDateSort = [];
        massivDateSort = massiv.slice().sort(function(a, b) { //сортировка масива по наименьшему чтоб не изменять исходный массив надо slice 
            return a.dateSec - b.dateSec
        })


        var mass2 = dateGraf(massivDateSort)
        console.log(mass2)
        // console.log(massivDateSort);
        graf1(mass2);
        //end 21.04
        var sumElem = 0;
        var zamerSort = [];
        for (var i = 0; i < massiv.length; i++) {
            console.log(massiv[i].zamer);
            if (massiv[i].zamer !== 10000) {
                var k = i;
                sumElem = sumElem + Number(massiv[i].zamer);
                zamerSort.push(+massiv[i].zamer)
            }

        }
        if (zamerSort.length >= 2) {
            console.log("сумма замеров " + sumElem);
            console.log("массив отсортированных замеров " + zamerSort)
            console.log("min " + massiv[0].zamer + "  max " + massiv[k].zamer); //min and max
            var interval = massiv[k].zamer - massiv[0].zamer;
            console.log(interval);
            var razrad = interval / 5;
            console.log(razrad);
            var num = 0,
                num2 = 0,
                num3 = 0,
                num4 = 0,
                num5 = 0,
                s = '',
                s1 = '',
                s2 = '',
                s3 = '',
                s4 = '';
            //к-средних начало
            const data = zamerSort;
            console.log(data);
            const res = skmeans(data, 5, [zamerSort[0], (+zamerSort[0] + 1.25 * razrad), (+zamerSort[0] + 2.5 * razrad), (+zamerSort[0] + 3.75 * razrad), (+zamerSort[0] + 5 * razrad)]);

            console.log(res);
            console.log(res.idxs);
            const id = res.idxs;

            function sovpad(k, massiv) {
                let n = 0,
                    viborka = [];

                for (i = 0; i < massiv.length; i++) {
                    if (id[i] === k) {
                        viborka.push(data[i])
                        console.log(data[i])
                        n++;
                    }

                }
                console.log("совпадений " + k + " количество " + n + " viborka ot" + viborka[0] + " do" + viborka[(viborka.length - 1)]);
                return {
                    viborka
                }
            }

            sovpad(0, id);
            sovpad(1, id);
            sovpad(2, id);
            sovpad(3, id);
            sovpad(4, id);

            let viborka1 = sovpad(0, id).viborka
            s = "от " + Math.round(+viborka1[0]) + "м до " + Math.round(+viborka1[(viborka1.length - 1)]) + "м";
            num = viborka1.length;
            console.log(s);

            let viborka2 = sovpad(1, id).viborka
            s1 = "от " + Math.round(+viborka2[0]) + "м до " + Math.round(+viborka2[(viborka2.length - 1)]) + "м";
            num2 = viborka2.length;
            console.log(s1);

            let viborka3 = sovpad(2, id).viborka
            s2 = "от " + Math.round(+viborka3[0]) + "м до " + Math.round(+viborka3[(viborka3.length - 1)]) + "м";
            num3 = viborka3.length;
            console.log(s2);

            let viborka4 = sovpad(3, id).viborka
            s3 = "от " + Math.round(+viborka4[0]) + "м до " + Math.round(+viborka4[(viborka4.length - 1)]) + "м";
            num4 = viborka4.length;
            console.log(s3);

            let viborka5 = sovpad(4, id).viborka
            s4 = "от " + Math.round(+viborka5[0]) + "м до " + Math.round(+viborka5[(viborka5.length - 1)]) + "м";
            num5 = viborka5.length;
            console.log(s4);
            //к-средних конец

            console.log(num, num2, num3, num4, num5);
            var elemNum = num + num2 + num3 + num4 + num5;

            function verayt(num, elemNum) {
                if ((num > 0) && (elemNum > 0)) {
                    num = Number(num)
                    elemNum = Number(elemNum)
                    return Math.round((num / elemNum) * 100)
                } else return 0
            }


            var percent1 = s + ", вероятность следующего повреждения " + verayt(num, elemNum) + " %"
            var percent2 = s1 + ", вероятность следующего повреждения " + verayt(num2, elemNum) + " %"
            var percent3 = s2 + ", вероятность следующего повреждения " + verayt(num3, elemNum) + " %"
            var percent4 = s3 + ", вероятность следующего повреждения " + verayt(num4, elemNum) + " %"
            var percent5 = s4 + ", вероятность следующего повреждения " + verayt(num5, elemNum) + " %"

            viewVer(percent1, percent2, percent3, percent4, percent5)
            //
            graf(num, num2, num3, num4, num5, s, s1, s2, s3, s4, "ТП");
        } else {
            $(".container-graf").hide();
            $(".statistic").hide();
        }
    }

    function searchBlizko() {
        massiv = [];
        var elems = document.getElementsByClassName('priv');
        var nashZamer = 0;
        nashZamer = Number(prompt('Введите замер', 0));
        console.log(nashZamer);
        if (nashZamer.toString() === "NaN") {
            return alert('вводите только цифры');
        }

        for (var i = 0; i < elems.length; i++) {

            var elem1 = elems[i].children[0].innerText
            var elem2 = elems[i].children[1].innerText
            console.log(elem1);
            console.log(elem2);

            var str = elem2;
            var result = str.match(/ \d+ м от /gi) //поиск замера из текста пробел м
            if (result === null) {
                result = str.match(/ \d+м от /gi) //поиск замера из текста без пробел м
            }
            if (result === null) {
                result = str.match(/ \d+м. от /gi) //поиск замера из текста без пробел м
            }
            if (result === null) {
                result = str.match(/ \d+ м. от /gi) //поиск замера из текста без пробел м
            }
            if (result !== null) {
                result[0] = result[0].replace(/[^-0-9]/gim, ''); //удаление лишних символов из замера
            } else {
                result = []
                result[0] = 10000
            }
            console.log(result[0]);
            var povr = {};
            povr.name = elem1;
            povr.priv = elem2;
            povr.zamer = result[0];
            povr.dZamer = Math.abs(result[0] - nashZamer);
            massiv.push(povr);

        }
        massiv.sort(function(a, b) { //сортировка масива по наименьшему
            return a.dZamer - b.dZamer
        })
        console.log(massiv);
    }

    function sortPovr() {
        for (var i = 0; i < massiv.length; i++) {
            var div = document.createElement('div'); //создание отсортированных элементов 
            if (massiv[i].zamer !== 0) {
                stroka = "<div  class='priv-sort'><div id='kl-name'>" + massiv[i].name + "</div><p id='kl-priv' class='bg-primary'>" + massiv[i].priv + "</p></div>";
            } else {
                stroka = "<div  class='priv-sort'><div id='kl-name'>" + massiv[i].name + "</div><p id='kl-priv' class='bg-danger'>" + massiv[i].priv + "</p></div>";
            }
            div.innerHTML = stroka;
            povrejdenia.appendChild(div);
        }
    }


    function sortPovrPn() {
        for (var i = 0; i < massiv.length; i++) {
            var div = document.createElement('div'); //создание отсортированных элементов 
            if (massiv[i].zamer !== 10000) {
                stroka = "<div  class='priv-sortPn'><div id='kl-name'>" + massiv[i].name + "</div><p id='kl-priv' class='bg-primary'>" + massiv[i].priv + " <p class='bg-info'>(" + massiv[i].opis + ")</p>" + "</p></div>";
            } else {
                stroka = "<div  class='priv-sortPn'><div id='kl-name'>" + massiv[i].name + "</div><p id='kl-priv' class='bg-danger'>" + massiv[i].priv + "</p></div>";
            }
            div.innerHTML = stroka;
            povrejdenia.appendChild(div);
        }
    }


    function sortPovrPC() {
        for (var i = 0; i < massiv.length; i++) {
            var div = document.createElement('div'); //создание отсортированных элементов 
            if (massiv[i].zamer !== 10000) {
                stroka = "<div  class='priv-sortPC'><div id='kl-name'>" + massiv[i].name + "</div><p id='kl-priv' class='bg-primary'>" + massiv[i].priv + " <p class='bg-info'>(" + massiv[i].opis + ")</p>" + "</p></div>";
            } else {
                stroka = "<div  class='priv-sortPC'><div id='kl-name'>" + massiv[i].name + "</div><p id='kl-priv' class='bg-danger'>" + massiv[i].priv + "</p></div>";
            }
            div.innerHTML = stroka;
            povrejdenia.appendChild(div);
        }
    }

    function sortPovrTn() {
        for (var i = 0; i < massiv.length; i++) {
            var div = document.createElement('div'); //создание отсортированных элементов 
            if (massiv[i].zamer !== 10000) {
                stroka = "<div  class='priv-sortTn'><div id='kl-name'>" + massiv[i].name + "</div><p id='kl-priv' class='bg-primary'>" + massiv[i].priv + " <p class='bg-info'>(" + massiv[i].opis + ")</p>" + "</p></div>";
            } else {
                stroka = "<div  class='priv-sortTn'><div id='kl-name'>" + massiv[i].name + "</div><p id='kl-priv' class='bg-danger'>" + massiv[i].priv + "</p></div>";
            }
            div.innerHTML = stroka;
            povrejdenia.appendChild(div);
        }
    }

    function sortBlizko() {
        // Удаление результатов предыдущего поиска 
        var node = document.getElementById("blizko");
        if (node !== null) {
            console.log(node);
            $('.priv-Blizko').remove();
        }
        for (var i = 0; i < massiv.length; i++) {
            var div = document.createElement('div'); //создание отсортированных элементов 
            stroka = "<div id='blizko'  class='priv-Blizko'><div id='kl-name'>" + massiv[i].name + "</div><p id='kl-priv' class='bg-info'>" + massiv[i].priv + "</p></div>";
            div.innerHTML = stroka;
            povrejdenia.appendChild(div);
        }

    }

    function viewVer(percent1, percent2, percent3, percent4, percent5) {
        // Удаление результатов предыдущего поиска 
        var node = document.getElementById("statistic");
        while (node.firstChild) {
            node.removeChild(node.firstChild);
        }
        var div = document.createElement('div'); //создание отсортированных элементов 
        stroka = "<div id='percent'  class='percent'><p id='priv-percent1' class='bg-danger'>" + percent1 +
            "</p><p id='priv-percent2' class='bg-warning'>" + percent2 + "</p><p id='priv-percent1' class='bg-danger'>" + percent3 +
            "</p><p id='priv-percent1' class='bg-warning'>" + percent4 +
            "</p><p id='priv-percent1' class='bg-danger'>" + percent5 +
            "</p></div>";
        div.innerHTML = stroka;
        statistic.appendChild(div);
    }

    function otRP(str) {
        var result = str.match(/ \d+ м от РП/gi) //поиск замера из текста пробел м
        if (result === null) {
            result = str.match(/ \d+м от РП/gi) //поиск замера из текста без пробел м
        }
        if (result === null) {
            result = str.match(/ \d+м. от РП/gi) //поиск замера из текста без пробел м
        }
        if (result === null) {
            result = str.match(/ \d+ м. от РП/gi) //поиск замера из текста без пробел м
        }
        if (result === null) {
            result = str.match(/ \d+ м от Рп/gi) //поиск замера из текста без пробел м
        }
        if (result === null) {
            result = str.match(/ \d+м от Рп/gi) //поиск замера из текста без пробел м
        }
        if (result === null) {
            result = str.match(/ \d+м. от Рп/gi) //поиск замера из текста без пробел м
        }
        if (result === null) {
            result = str.match(/ \d+ м. от Рп/gi) //поиск замера из текста без пробел м
        }
        if (result !== null) {
            result[0] = result[0].replace(/[^-0-9]/gim, ''); //удаление лишних символов из замера
        } else {
            result = []
            result[0] = 10000
        }
        return result[0]
    }

    function otRP1(str) {
        let searchMy = {}
        searchMy.opis = "нет"
        var result = str.match(/ \d+ м от РП/gi) //поиск замера из текста пробел м
        if (result === null) {
            result = str.match(/ \d+м от РП/gi) //поиск замера из текста без пробел м
        }
        if (result === null) {
            result = str.match(/ \d+м. от РП/gi) //поиск замера из текста без пробел м
        }
        if (result === null) {
            result = str.match(/ \d+ м. от РП/gi) //поиск замера из текста без пробел м
        }
        if (result === null) {
            result = str.match(/ \d+ м от Рп/gi) //поиск замера из текста без пробел м
        }
        if (result === null) {
            result = str.match(/ \d+м от Рп/gi) //поиск замера из текста без пробел м
        }
        if (result === null) {
            result = str.match(/ \d+м. от Рп/gi) //поиск замера из текста без пробел м
        }
        if (result === null) {
            result = str.match(/ \d+ м. от Рп/gi) //поиск замера из текста без пробел м
        }
        if (result !== null) {
            result[0] = result[0].replace(/[^-0-9]/gim, ''); //удаление лишних символов из замера
        } else {
            result = []
            let all_lenght1 = all_lenght(str)
            console.log("вся длина " + all_lenght1)
            let otPC1 = otPC(str)
            console.log("от ПС " + otPC1)
            if ((all_lenght1 !== undefined) && (otPC1 !== undefined) && (otPC1 !== 10000)) {

                result[0] = Number(all_lenght1) - Number(otPC1)
                console.log("замер=вся длина - расстояние от ПС" + Number(all_lenght1) + "-" + Number(otPC1) + "=" + result[0]);
                searchMy.opis = "замер=вся длина - расстояние от ПС: " + Number(all_lenght1) + "-" + Number(otPC1) + " = " + result[0] + "м от РП"
            } else if ((average_lenght() !== 0) && (all_lenght1 === undefined) && (otPC1 !== 10000)) {
                result[0] = average_lenght() - Number(otPC1)
                console.log("замер=средняя вся длина - расстояние от ПС" + average_lenght() + "-" + Number(otPC1) + "=" + result[0]);
                searchMy.opis = "замер=средняя вся длина - расстояние от ПС: " + average_lenght() + "-" + Number(otPC1) + " = " + result[0] + "м от РП"
            } else {
                result[0] = 10000
            }
        }
        searchMy.result = result[0]
        return searchMy
    }

    function otPC(str) {
        var result = str.match(/ \d+ м от ПС/gi) //поиск замера из текста пробел м
        if (result === null) {
            result = str.match(/ \d+м от ПС/gi) //поиск замера из текста без пробел м
        }
        if (result === null) {
            result = str.match(/ \d+м. от ПС/gi) //поиск замера из текста без пробел м
        }
        if (result === null) {
            result = str.match(/ \d+ м. от ПС/gi) //поиск замера из текста без пробел м
        }
        if (result === null) {
            result = str.match(/ \d+ м от Пс/gi) //поиск замера из текста без пробел м
        }
        if (result === null) {
            result = str.match(/ \d+м от Пс/gi) //поиск замера из текста без пробел м
        }
        if (result === null) {
            result = str.match(/ \d+м. от Пс/gi) //поиск замера из текста без пробел м
        }
        if (result === null) {
            result = str.match(/ \d+ м. от Пс/gi) //поиск замера из текста без пробел м
        }
        if (result === null) {
            result = str.match(/ \d+ м от от ПС/gi) //поиск замера из текста без пробел м
        }
        if (result === null) {
            result = str.match(/ \d+ мот ПС/gi) //поиск замера из текста без пробел м
        }
        if (result === null) {
            result = str.match(/ \d+мот ПС/gi) //поиск замера из текста без пробел м
        }
        if (result !== null) {
            result[0] = result[0].replace(/[^-0-9]/gim, ''); //удаление лишних символов из замера
        } else {
            result = []
            result[0] = 10000
        }
        return result[0]
    }

    function otPC1(str) {
        let searchMy = {}
        searchMy.opis = "нет"
        var result = str.match(/ \d+ м от ПС/gi) //поиск замера из текста пробел м
        if (result === null) {
            result = str.match(/ \d+м от ПС/gi) //поиск замера из текста без пробел м
        }
        if (result === null) {
            result = str.match(/ \d+м. от ПС/gi) //поиск замера из текста без пробел м
        }
        if (result === null) {
            result = str.match(/ \d+ м. от ПС/gi) //поиск замера из текста без пробел м
        }
        if (result === null) {
            result = str.match(/ \d+ м от Пс/gi) //поиск замера из текста без пробел м
        }
        if (result === null) {
            result = str.match(/ \d+м от Пс/gi) //поиск замера из текста без пробел м
        }
        if (result === null) {
            result = str.match(/ \d+м. от Пс/gi) //поиск замера из текста без пробел м
        }
        if (result === null) {
            result = str.match(/ \d+ м. от Пс/gi) //поиск замера из текста без пробел м
        }
        if (result === null) {
            result = str.match(/ \d+ м от от ПС/gi) //поиск замера из текста без пробел м
        }
        if (result === null) {
            result = str.match(/ \d+ мот ПС/gi) //поиск замера из текста без пробел м
        }
        if (result === null) {
            result = str.match(/ \d+мот ПС/gi) //поиск замера из текста без пробел м
        }
        if (result !== null) {
            result[0] = result[0].replace(/[^-0-9]/gim, ''); //удаление лишних символов из замера
        } else {
            result = []
            let all_lenght1 = all_lenght(str)
            console.log("вся длина " + all_lenght1)
            let otRP1 = otRP(str)
            console.log("от РП " + otRP1)
            if ((all_lenght1 !== undefined) && (otRP1 !== undefined) && (otRP1 !== 10000)) {

                result[0] = Number(all_lenght1) - Number(otRP1)
                console.log("замер=вся длина - расстояние от РП" + Number(all_lenght1) + "-" + Number(otRP1) + "=" + result[0]);
                searchMy.opis = "замер=вся длина - расстояние от РП: " + Number(all_lenght1) + "-" + Number(otRP1) + " = " + result[0] + "м от ПС"
            } else if ((average_lenght() !== 0) && (all_lenght1 === undefined) && (otRP1 !== 10000)) {
                result[0] = average_lenght() - Number(otRP1)
                console.log("замер=средняя вся длина - расстояние от РП" + average_lenght() + "-" + Number(otRP1) + "=" + result[0]);
                searchMy.opis = "замер=средняя вся длина - расстояние от РП: " + average_lenght() + "-" + Number(otRP1) + " = " + result[0] + "м от ПС"
            } else {
                result[0] = 10000
            }
        }
        searchMy.result = result[0]
        return searchMy
    }

    function otTP(str) {
        var result = str.match(/ \d+ м от ТП/gi) //поиск замера из текста пробел м
        if (result === null) {
            result = str.match(/ \d+м от ТП/gi) //поиск замера из текста без пробел м
        }
        if (result === null) {
            result = str.match(/ \d+м. от ТП/gi) //поиск замера из текста без пробел м
        }
        if (result === null) {
            result = str.match(/ \d+ м. от ТП/gi) //поиск замера из текста без пробел м
        }
        if (result === null) {
            result = str.match(/ \d+ м от Тп/gi) //поиск замера из текста без пробел м
        }
        if (result === null) {
            result = str.match(/ \d+м от Тп/gi) //поиск замера из текста без пробел м
        }
        if (result === null) {
            result = str.match(/ \d+м. от Тп/gi) //поиск замера из текста без пробел м
        }
        if (result === null) {
            result = str.match(/ \d+ м. от Тп/gi) //поиск замера из текста без пробел м
        }
        if (result !== null) {
            result[0] = result[0].replace(/[^-0-9]/gim, ''); //удаление лишних символов из замера
        } else {
            result = []
            result[0] = 10000
        }
        return result[0]
    }

    function otTP1(str) {
        let searchMy = {}
        searchMy.opis = "нет"
        var result = str.match(/ \d+ м от ТП/gi) //поиск замера из текста пробел м
        if (result === null) {
            result = str.match(/ \d+м от ТП/gi) //поиск замера из текста без пробел м
        }
        if (result === null) {
            result = str.match(/ \d+м. от ТП/gi) //поиск замера из текста без пробел м
        }
        if (result === null) {
            result = str.match(/ \d+ м. от ТП/gi) //поиск замера из текста без пробел м
        }
        if (result === null) {
            result = str.match(/ \d+ м от Тп/gi) //поиск замера из текста без пробел м
        }
        if (result === null) {
            result = str.match(/ \d+м от Тп/gi) //поиск замера из текста без пробел м
        }
        if (result === null) {
            result = str.match(/ \d+м. от Тп/gi) //поиск замера из текста без пробел м
        }
        if (result === null) {
            result = str.match(/ \d+ м. от Тп/gi) //поиск замера из текста без пробел м
        }
        if (result !== null) {
            result[0] = result[0].replace(/[^-0-9]/gim, ''); //удаление лишних символов из замера
        } else {
            result = []
            let all_lenght1 = all_lenght(str)
            console.log("вся длина " + all_lenght1)
            let otRP1 = otRP(str)
            console.log("от РП " + otRP1)
            if ((all_lenght1 !== undefined) && (otRP1 !== undefined) && (otRP1 !== 10000)) {
                result[0] = Number(all_lenght1) - Number(otRP1)
                console.log("замер=вся длина - расстояние от РП" + Number(all_lenght1) + "-" + Number(otRP1) + "=" + result[0]);
                searchMy.opis = "замер=вся длина - расстояние от РП: " + Number(all_lenght1) + "-" + Number(otRP1) + " = " + result[0] + "м от ТП"
            } else if ((average_lenght() !== 0) && (all_lenght1 === undefined) && (otRP1 !== 10000)) {
                result[0] = average_lenght() - Number(otRP1)
                console.log("замер=средняя вся длина - расстояние от РП" + average_lenght() + "-" + Number(otRP1) + "=" + result[0]);
                searchMy.opis = "замер=средняя вся длина - расстояние от РП: " + average_lenght() + "-" + Number(otRP1) + " = " + result[0] + "м от ТП"
            } else {
                result[0] = 10000
            }
        }
        searchMy.result = result[0]
        return searchMy
    }
    //поиск всей длинны
    function all_lenght(str) {
        let result = []
        result = str.match(/вся длинна: \d+м/gi) //поиск длинны
        if (result !== null) {
            result[0] = result[0].replace(/[^-0-9]/gim, ''); //удаление лишних символов длинны
            console.log("вся длина " + result[0]);
            return result[0]
        }
    }
    //конец поиска всей длинны

    //поиск средней длины 
    function average_lenght() {
        let obj_priv = {}
        var elems = document.getElementsByClassName('priv');
        for (var i = 0; i < elems.length; i++) {
            obj_priv[i] = elems[i].children[1].innerText
        }
        lenght_massiv = []
        let average = 0
        for (let element in obj_priv) {
            str1 = obj_priv[element]
            // console.log("elements"+obj[element])
            all_lenght1 = all_lenght(str1)
            // console.log("all_lenght1"+all_lenght1)
            if (all_lenght1 !== undefined) {
                lenght_massiv.push(Number(all_lenght1))
            }
        }
        if (lenght_massiv.length !== 0) {
            console.log("массив длин  " + lenght_massiv);
            const reducer = (accumulator, currentValue) => accumulator + currentValue;
            let sum = lenght_massiv.reduce(reducer)
            average = Math.round(sum / lenght_massiv.length)
            console.log("ср длин  " + average);
        }
        return average
    }
    //конец поиск ср длины

    //поиск даты
    function searchDate(str) {
        var resultDate = str.match(/([0-2]\d|3[01])\.(0\d|1[012])\.(\d{4})/gi) //поиск замера из текста пробел м
        if (resultDate === null) {
            resultDate = str.match(/((\d{1,2} января)|(\d{1,2} февраля)|(\d{1,2} марта)|(\d{1,2} апреля)|(\d{1,2} мая)|(\d{1,2} июня)|(\d{1,2} июля)|(\d{1,2} августа)|(\d{1,2} сентября)|(\d{1,2} октября)|(\d{1,2} ноября)|(\d{1,2} декабря)) (\d{4})/gi)

            let kStr = str.match(/(\d{1,2} января) (\d{4})/gi)
            if (kStr !== null) {
                // resultDate = kStr
                let k = kStr[0].replace(" января ", ".01.")
                //console.log(kStr);
                resultDate[0] = k
            }

            kStr = str.match(/(\d{1,2} февраля) (\d{4})/gi)
            if (kStr !== null) {
                // resultDate = kStr
                let k = kStr[0].replace(" февраля ", ".02.")
                //console.log(kStr);
                resultDate[0] = k
            }
            kStr = str.match(/(\d{1,2} марта) (\d{4})/gi)
            if (kStr !== null) {
                // resultDate = kStr
                let k = kStr[0].replace(" марта ", ".03.")
                //console.log(kStr);
                resultDate[0] = k
            }
            kStr = str.match(/(\d{1,2} апреля) (\d{4})/gi)
            if (kStr !== null) {
                // resultDate = kStr
                let k = kStr[0].replace(" апреля ", ".04.")
                //console.log(kStr);
                resultDate[0] = k
            }
            kStr = str.match(/(\d{1,2} мая) (\d{4})/gi)
            if (kStr !== null) {
                // resultDate = kStr
                let k = kStr[0].replace(" мая ", ".05.")
                //console.log(kStr);
                resultDate[0] = k
            }
            kStr = str.match(/(\d{1,2} июня) (\d{4})/gi)
            if (kStr !== null) {
                // resultDate = kStr
                let k = kStr[0].replace(" июня ", ".06.")
                //console.log(kStr);
                resultDate[0] = k
            }
            kStr = str.match(/(\d{1,2} июля) (\d{4})/gi)
            if (kStr !== null) {
                // resultDate = kStr
                let k = kStr[0].replace(" июля ", ".07.")
                //console.log(kStr);
                resultDate[0] = k
            }
            kStr = str.match(/(\d{1,2} августа) (\d{4})/gi)
            if (kStr !== null) {
                // resultDate = kStr
                let k = kStr[0].replace(" августа ", ".08.")
                //console.log(kStr);
                resultDate[0] = k
            }
            kStr = str.match(/(\d{1,2} сентября) (\d{4})/gi)
            if (kStr !== null) {
                // resultDate = kStr
                let k = kStr[0].replace(" сентября ", ".09.")
                //console.log(kStr);
                resultDate[0] = k
            }
            kStr = str.match(/(\d{1,2} октября) (\d{4})/gi)
            if (kStr !== null) {
                // resultDate = kStr
                let k = kStr[0].replace(" октября ", ".10.")
                //console.log(kStr);
                resultDate[0] = k
            }
            kStr = str.match(/(\d{1,2} ноября) (\d{4})/gi)
            if (kStr !== null) {
                // resultDate = kStr
                let k = kStr[0].replace(" ноября ", ".11.")
                //console.log(kStr);
                resultDate[0] = k
            }
            kStr = str.match(/(\d{1,2} декабря) (\d{4})/gi)
            if (kStr !== null) {
                // resultDate = kStr
                let k = kStr[0].replace(" декабря ", ".12.")
                //console.log(kStr);
                resultDate[0] = k
            }

        }
        return resultDate
    }

    function dateGraf(massivDateSort) { //создаем новый массив для графика 2
        let f = []
        for (var i = 0; i < massivDateSort.length; i++) {
            let x = massivDateSort[i].date
            // x=x.substring(x.length - 6)
            let y = massivDateSort[i].zamer
            if (((+y) > 0) && ((+y) < 10000)) {
                let z = [x, +y]
                f.push(z)
            }
        }
        return f
    }

    function sel() {
        var sel = $("#my_select option:selected").val();
        switch (sel) {
            case '1':

                searchZamer(); //поиск замеров 
                sortPovr(); //вывод сортированных элементов на экран
                $(".priv-sort").show();
                $(".priv").hide();
                $(".priv-sortPn").hide();
                $(".priv-sortPC").hide();
                $(".priv-sortTn").hide();
                $(".priv-Blizko").hide();
                $(".container-graf").hide();
                $(".statistic").hide();
                break;
            case '2':
                $(".container-graf").show();
                $(".statistic").show();
                searchZamerPn(); //поиск замеров 
                sortPovrPn(); //вывод сортированных элементов на экран
                $(".priv-sortPn").show();
                $(".priv").hide();
                $(".priv-sort").hide();
                $(".priv-sortPC").hide();
                $(".priv-sortTn").hide();
                $(".priv-Blizko").hide();

                break;
            case '3':
                $(".container-graf").show();
                $(".statistic").show();
                searchZamerPC(); //поиск замеров 
                sortPovrPC(); //вывод сортированных элементов на экран
                $(".priv-sortPC").show();
                $(".priv").hide();
                $(".priv-sort").hide();
                $(".priv-sortPn").hide();
                $(".priv-sortTn").hide();
                $(".priv-Blizko").hide();

                break;
            case '4':
                $(".container-graf").show();
                $(".statistic").show();
                searchZamerTn(); //поиск замеров 
                sortPovrTn(); //вывод сортированных элементов на экран
                $(".priv-sortTn").show();
                $(".priv").hide();
                $(".priv-sort").hide();
                $(".priv-sortPn").hide();
                $(".priv-sortPC").hide();
                $(".priv-Blizko").hide();

                break;
            case '5':
                searchBlizko(); //поиск замеров 
                $(".priv-Blizko").show();
                sortBlizko(); //вывод сортированных элементов на экран
                $(".priv-sortTn").hide();
                $(".priv").hide();
                $(".priv-sort").hide();
                $(".priv-sortPn").hide();
                $(".priv-sortPC").hide();
                $(".container-graf").hide();
                $(".statistic").hide();
                break;
            default:
                $(".container-graf").show();
                $(".statistic").show();
                $(".priv-sort").hide(); //скрыть элементы сортировки
                $(".priv-sortPn").hide();
                $(".priv-sortPC").hide();
                $(".priv-sortTn").hide();
                $(".priv-Blizko").hide();
                $(".priv").show();
        }
    }
</script>

</html>