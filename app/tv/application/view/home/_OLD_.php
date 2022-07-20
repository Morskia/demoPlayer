<?php
header( 'Access-Control-Allow-Origin: *' );


?>
<!DOCTYPE html>
<html lang="en" class="h-100">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title> MBOX STUDIOS </title>
    <link href="css/colors.css" rel="stylesheet">
    <link href="css/front.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/53aa1a9925.js" crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
          rel="stylesheet">
    <script src="js/jquery-3.4.0.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="js/common.js"></script>
    <script src="js/jquery-ui.js"></script>
    <script>
        const localHost = "localhost/";
        let advertisingTimeOut;
        const updateWeather = city => {
            fetch('http://api.weatherapi.com/v1/current.json?key=57ae8db30b514f98a40125028210411&q=' + city + '&aqi=no')
                .then(response => response.json())
                .then(data => {
                    document.getElementById('degree').innerHTML = data.current.temp_c;
                    document.getElementById('icon').src = data.current.condition.icon;
                });
        }
    </script>
</head>
<body id="enter" class="position-relative d-flex justify-content-center align-items-center">
<?php
$videos = array();
foreach ( $playlist as $video ) {
	$link = str_replace( "../public/", "", $video[0] );
	array_push( $videos, $link );
};

?>

<button id="start"><i class="far fa-play-circle"></i></button>
<div id="screen-layout">
    <div id="advertising">
        <video muted id="advertising_player"></video>
    </div>
    <div id="appendPlayer" style="position: absolute; left: 0; top: 0; z-index: 1">
		<?php if ( count( $videos ) > 0 ) { ?>
            <video muted autoplay id="player"
                   src="<?php echo $videos[0]; ?>"></video>
		<?php } else {
			echo "<h2 id='noinfo'>Няма въведена програма за този ден!</h2>";
		} ?>
    </div>
	<?php

	if ( gettype( $layout ) == 'string' ) {
		$layout = json_decode( $layout, true );
	}

	foreach ( $layout["save"] ?? array() as $key => $element ) {
		switch ( $key ) {
			case 'activate_logo':
				$coordinates = explode( ',', $element );
				$img         = $layout['featured_image'];
				if ( count( $coordinates ) == 1 ) {
					$coordinates = array( "0", "0" );
				};
				$img = SERVER . $this->helpers::big( $img );
				echo '<div id="logoholder" style="left: ' . $coordinates[0] . '% ; top:  ' . $coordinates[1] . '%" class="layoutElements"><img   src="' . $img . '"></div>';
				break;
			case 'activate_weather':
				$coordinates = explode( ',', $element );
				if ( count( $coordinates ) == 1 ) {
					$coordinates = array( "0", "0" );
				};
				echo '<div id="weather" style="left: ' . $coordinates[0] . '% ; top:  ' . $coordinates[1] . '%" class="layoutElements">
                                        <span id="degree"></span><span>°C</span>
                                        <img id="icon" src="">
                                </div>';
				break;

			case 'active_scroll':
				$coordinates = explode( ',', $element );
				$txt         = $layout['save']['txt_infodata'];
				$bg          = $layout['save']['bg_infodata'];
				$info        = $layout['activate_infodata'];
				if ( count( $coordinates ) == 1 ) {
					$coordinates = array( "0", "0" );
				};
				echo '<div style="width: 100vw; left: 0; top:  ' . $coordinates[1] . '%;" class="layoutElements">
   <div class="bar" style="background-color: ' . $bg . '; color: ' . $txt . '"> 
   <span class="bar_content">' . $info . '</span> </div>
    </div>';
				break;

			case 'activate_clock':

				$coordinates = explode( ',', $element );

				$txt  = $layout['save']['txt_clock'];
				$bg   = $layout['save']['bg_clock'];
				$size = $layout['save']['clock_size'];
				if ( count( $coordinates ) == 1 ) {
					$coordinates = array( "0", "0" );
				};
				echo '<div class="layoutElements p-3 ' . $size . '" id="clock"  style="left: ' . $coordinates[0] . '%; top:  ' . $coordinates[1] . '%; background-color: ' . $bg . '; color: ' . $txt . '">
                             <span id="add_time"></span> 
                             </div>';
				break;
		};
	} ?>
</div>
<script>
    const SERVER = "<?php echo SERVER; ?>";
    let weatherInterval;
    const Player = document.getElementById('player');
    const days = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];

    let city = " <?php echo $layout['grad'] ?? 'Varna'; ?>";
    let currentDayInit = new Date();
    let currentDay = currentDayInit.getDay();
    if (document.getElementById('weather')) {
        updateWeather(city);
        weatherInterval = setInterval(_ => {
            updateWeather(city);
        }, 3600000);
    }
    let nextsrc = <?php echo json_encode( $videos, JSON_UNESCAPED_SLASHES ); ?>;
    let elm = 0;

    function startVideo() {
        playPromise = Player.play();
        if (playPromise !== undefined) {
            playPromise.then(function () {
            }).catch(function (error) {
                let brokenVideo = nextsrc[elm];
                ++elm;
                if (elm > nextsrc.length) {
                    elm = 0;
                }
                Player.src = nextsrc[elm];
                startVideo();
                fetch("<?php echo SERVER; ?>clipProblem",
                    {
                        method: "POST",
                        body: JSON.stringify({
                            'broken': brokenVideo,
                            'location': "<?php echo str_replace( ' ', '', $_SESSION['locationName'] ); ?>",
                            'camefrom': 'програмата'
                        })
                    });

            });
        }
    }

    if (document.getElementById('player')) {
        Player.onended = function () {
            let checkDayInit = new Date();
            let checkDay = checkDayInit.getDay();
            console.log('newDay', currentDay, checkDay);
            if (currentDay != checkDay) {
                currentDay = checkDay;
                ['sch', 'adv'].forEach(el => {
                    switch (el) {
                        case 'adv':
                            fetch('admin/getadvertising')
                                .then(response => response.json())
                                .then(data => {
                                    let rawData = data.map(el => unserialize(el.data));
                                    if (rawData.length > 0) {
                                        let sortedData = Object.entries(rawData[0]).map(el => flattenObj(el)).sort((a, b) => a.startTime > b.startTime && 1 || -1);
                                        playAdvertising(sortedData)
                                    }
                                });
                            break;
                        case 'sch':
                            fetch("/tv/newSchedule",
                                {
                                    headers: {
                                        'Accept': 'application/json',
                                        'Content-Type': 'application/json'
                                    },
                                    method: "POST",
                                    body: JSON.stringify({'day': days[currentDay]})
                                })
                                .then(response => response.text())
                                .then(data => {
                                    let newPlayList = JSON.parse(data).map(el => el.video.replace('../public/', ''));
                                    nextsrc = newPlayList;
                                    elm = 0;
                                    Player.src = nextsrc[elm];
                                    startVideo();
                                });

                            break;
                    }
                });
                return;
            }
            if (++elm < nextsrc.length) {
                Player.src = nextsrc[elm];
                startVideo();
            } else {
                elm = 0;
                Player.src = nextsrc[elm];
                startVideo();
            }
        }
    }


</script>
</body>
</html>
<script>
    let timerStart;

    function startTime() {

        const today = new Date().toLocaleString('bg-BG', {timeZone: 'Europe/Sofia'})
        document.getElementById('add_time').innerHTML = today.split(',')[1];
        timerStart = setTimeout(startTime, 1000);
    }

    function GoInFullscreen(element) {
        if (element.requestFullscreen)
            element.requestFullscreen();
        else if (element.mozRequestFullScreen)
            element.mozRequestFullScreen();
        else if (element.webkitRequestFullscreen)
            element.webkitRequestFullscreen();
        else if (element.msRequestFullscreen)
            element.msRequestFullscreen();
    }

    function GoOutFullscreen() {
        if (document.exitFullscreen)
            document.exitFullscreen();
        else if (document.mozCancelFullScreen)
            document.mozCancelFullScreen();
        else if (document.webkitExitFullscreen)
            document.webkitExitFullscreen();
        else if (document.msExitFullscreen)
            document.msExitFullscreen();
    }

    let offlineInterval;
    const offlineMode = _ => {
        console.log('beginoffline');
        offlineInterval = setInterval(_ => {
            ['lay', 'adv', 'sch'].forEach(el => {
                switch (el) {
                    case 'lay':
                        fetch("<?php echo SERVER; ?>getnewlayout?location=<?php echo $_SESSION['id']; ?>")
                            .then(response => response.text())
                            .then(data => {
                                fetch("/tv/updateLocalLayoutData", {
                                    method: 'POST',
                                    body: JSON.stringify({'layout': data})
                                }).then(response => response.text()).then(data => console.log(data));
                                let info = JSON.parse(data);
                                document.querySelectorAll('.layoutElements').forEach(el => {
                                    el.remove();
                                });
                                console.log(Object.entries(info.save));
                                Object.entries(info.save).map(el => {
                                    switch (el[0]) {
                                        case "activate_logo":
                                            coordinates = el[1].split(",");
                                            div = document.createElement('div');
                                            div.id = "logoholder";
                                            div.style.left = coordinates[0] + '%';
                                            div.style.top = coordinates[1] + '%';
                                            div.classList.add('layoutElements');
                                            let img = document.createElement('img');
                                            img.src = SERVER + info.featured_image.replace('thumbnails', 'publications');
                                            div.appendChild(img);
                                            document.getElementById('screen-layout').appendChild(div);
                                            break;
                                        case 'activate_weather':
                                            coordinates = el[1].split(",");
                                            div = document.createElement('div');
                                            div.id = "weather";
                                            div.style.left = coordinates[0] + '%';
                                            div.style.top = coordinates[1] + '%';
                                            div.classList.add('layoutElements');
                                            div.innerHTML = '<span id="degree"></span><span>°C</span> <img id="icon" src="">';
                                            document.getElementById('screen-layout').appendChild(div);
                                            updateWeather(info.grad);
                                            clearInterval(weatherInterval);
                                            weatherInterval = setInterval(_ => {
                                                updateWeather(info.grad);
                                            }, 3600000);

                                            break;
                                        case 'active_scroll':
                                            coordinates = el[1].split(",");
                                            div = document.createElement('div');
                                            div.id = "weather";
                                            div.style.left = '0%';
                                            div.style.top = coordinates[1] + '%';
                                            div.style.width = '100vw';
                                            div.classList.add('layoutElements');
                                            div.innerHTML = `<div class="bar" style="background-color: ${info.save['bg_infodata']} ; color: ${info.save['txt_infodata']}">
                                                 <span class="bar_content">${info.activate_infodata}</span> </div>`;
                                            document.getElementById('screen-layout').appendChild(div);
                                            break;
                                        case 'activate_clock':
                                            clearTimeout(timerStart);
                                            coordinates = el[1].split(",");
                                            div = document.createElement('div');
                                            div.id = "clock";
                                            div.style.left = coordinates[0] + '%';
                                            div.style.top = coordinates[1] + '%';
                                            div.style.backgroundColor = info.save['bg_clock'];
                                            div.style.color = info.save['txt_clock'];
                                            div.classList.add('layoutElements', `${info.save['clock_size']}`);
                                            div.innerHTML = `<span id="add_time"></span>`;
                                            document.getElementById('screen-layout').appendChild(div);
                                            startTime();
                                            break;
                                    }
                                })
                            });
                        break;
                    case 'adv':
                        fetch('admin/getnewadvertising')
                            .then(response => response.text()).then(data => getAdvertising());
                        break;
                    case 'sch':
                        fetch('admin/getnewschedule')
                            .then(response => response.text())
                            .then(data => {
                                fetch("/tv/newSchedule",
                                    {
                                        headers: {
                                            'Accept': 'application/json',
                                            'Content-Type': 'application/json'
                                        },
                                        method: "POST",
                                        body: JSON.stringify({'day': days[currentDay]})
                                    })
                                    .then(response => response.text())
                                    .then(data => {
                                        let newPlayList = JSON.parse(data).map(el => el.video.replace('../public/', ''));
                                        nextsrc = newPlayList;
                                        if (document.getElementById('noinfo')) {
                                            document.getElementById('noinfo').remove();
                                            newPlayer = document.createElement('video');
                                            newPlayer.id = 'player';
                                            newPlayer.muted = true;
                                            newPlayer.autoplay = 'autoplay';
                                            document.getElementById('appendPlayer').append(newPlayer);
                                        }
                                        document.getElementById('player').src = nextsrc[elm];
                                        startVideo();
                                    });
                            });
                        break;
                }
            })
        }, 3600000)
    };

    function IsFullScreenCurrently() {
        let full_screen_element = document.fullscreenElement || document.webkitFullscreenElement || document.mozFullScreenElement || document.msFullscreenElement || null;
        if (full_screen_element === null)
            return false;
        else
            return true;
    }

    $("#start").on('click', function () {
        if (IsFullScreenCurrently()) {
            GoOutFullscreen();
        } else {
            document.getElementById('screen-layout').style.display = 'block';
            document.getElementById('start').style.display = 'none';
            if (document.getElementById('player')) {
                document.getElementById('player').style.display = 'block';
                startVideo();
            }
            GoInFullscreen($("body").get(0));
        }
    });
    $(document).on('fullscreenchange webkitfullscreenchange mozfullscreenchange MSFullscreenChange', function () {
        if (IsFullScreenCurrently()) {
            $("#element span").text('Full Screen Mode Enabled');
            $("#go-button").text('Disable Full Screen');
        } else {
            document.getElementById('screen-layout').style.display = 'none';
            document.getElementById('start').style.display = 'block';
            document.getElementById('player').pause();
        }
    });
    const playAdvertising = advertisingBlock => {
        console.log('new', advertisingBlock);
        clearTimeout(advertisingTimeOut);
        let counter = 0;

        function doAdvetising() {
            let currentTime = new Date().toLocaleString('bg-BG', {timeZone: 'Europe/Sofia'}).split(',')[1].split(':');
            let timeIs = (+currentTime[0].trim()) * 60 * 60 + (currentTime[1]) * 60;
            if (counter < advertisingBlock.length) {
                if (timeIs > advertisingBlock[counter].startTime) {
                    counter += 1;
                    doAdvetising();
                } else {
                    //  let calculateDelay = parseInt(advertisingBlock[counter].startTime) - parseInt(timeIs) * 1000;
                    let calculateDelay = (parseInt(advertisingBlock[counter].startTime) - parseInt(timeIs)) * 1000;
                    if (calculateDelay < 0) {
                        let hm = advertisingBlock[counter].start;
                        let [hours, minutes] = hm.split(':');
                        let newStartTime = (+hours) * 60 * 60 + (+minutes) * 60 * 1000
                        calculateDelay = (newStartTime - parseInt(timeIs)) * 1000;

                    }
                    console.log('prepare', advertisingBlock[counter].element, calculateDelay)
                    advertisingTimeOut = setTimeout(_ => {
                        switch (advertisingBlock[counter].type) {
                            case 'video':
                                Player.pause();
                                document.getElementById('advertising').style.zIndex = 2;
                                const AdvertisingPlayer = document.getElementById('advertising_player');
                                AdvertisingPlayer.src = advertisingBlock[counter].element;
                                let doAdvertisingPlayer = AdvertisingPlayer.play();
                                if (doAdvertisingPlayer !== undefined) {
                                    doAdvertisingPlayer.then(function () {
                                    }).catch(function (error) {
                                        Player.play();
                                        document.getElementById('advertising').style.zIndex = 1;
                                        fetch("<?php echo SERVER; ?>clipProblem",
                                            {
                                                method: "POST",
                                                body: JSON.stringify({
                                                    'broken': advertisingBlock[counter].element,
                                                    'location': "<?php echo str_replace( ' ', '', $_SESSION['locationName'] ); ?>",
                                                    'camefrom': 'реклама'
                                                })
                                            });
                                        counter += 1;
                                        doAdvetising();
                                    });
                                }
                                AdvertisingPlayer.onended = _ => {
                                    if (typeof advertisingBlock[counter + 1] !== 'undefined' && advertisingBlock[counter].startTime == advertisingBlock[counter + 1].startTime) {
                                        AdvertisingPlayer.src = advertisingBlock[counter + 1].element;
                                        let doubleAdvertisingPlayer = AdvertisingPlayer.play();
                                        if (doubleAdvertisingPlayer !== undefined) {
                                            doubleAdvertisingPlayer.then(function () {
                                            }).catch(function (error) {
                                                Player.play();
                                                document.getElementById('advertising').style.zIndex = 1;
                                                fetch("<?php echo SERVER; ?>clipProblem",
                                                    {
                                                        method: "POST",
                                                        body: JSON.stringify({
                                                            'broken': advertisingBlock[counter + 1].element,
                                                            'location': "<?php echo str_replace( ' ', '', $_SESSION['locationName'] ); ?>",
                                                            'camefrom': 'двойна-реклама'
                                                        })
                                                    });

                                            });
                                        }
                                        counter += 1;
                                    } else {
                                        Player.play();
                                        document.getElementById('advertising').style.zIndex = 1;
                                        counter += 1;
                                        doAdvetising();
                                    }
                                };
                                break;
                            case 'image':
                                let holder = document.getElementById('advertising');
                                let image = document.createElement('img');
                                image.src = `<?php echo SERVER; ?>/${advertisingBlock[counter].element.replace('thumbnails', 'publications')}`;
                                image.style.left = `${advertisingBlock[counter].left}%`;
                                image.style.top = `${advertisingBlock[counter].top}%`;
                                holder.appendChild(image);
                                setTimeout(_ => {
                                    image.remove();
                                }, parseInt(advertisingBlock[counter].duration) * 60 * 1000);
                                counter += 1;
                                doAdvetising();
                                break;
                            case 'text':
                                let holderTxt = document.getElementById('advertising');
                                let txt = document.createElement('div');
                                txt.id = 'textHolder';
                                txt.innerHTML = `${advertisingBlock[counter].element}`;
                                txt.style.left = `${advertisingBlock[counter].left}%`;
                                txt.style.top = `${advertisingBlock[counter].top}%`;
                                txt.style.backgroundColor = `#000`;
                                txt.style.color = `#fff`;
                                // txt.style.backgroundColor = `${advertisingBlock[counter].backgroundColor}`;
                                // txt.style.color = `${advertisingBlock[counter].color}`;
                                holderTxt.appendChild(txt);
                                setTimeout(_ => {
                                    txt.remove();
                                }, parseInt(advertisingBlock[counter].duration) * 60 * 1000);
                                counter += 1;
                                doAdvetising();
                                break;
                        }

                    }, calculateDelay)
                }
            }
        }

        doAdvetising()
    };

    const getAdvertising = _ => {
        fetch('admin/getadvertising')
            .then(response => response.json())
            .then(data => {
                let rawData = data.map(el => unserialize(el.data));
                // console.log(rawData)
                if (rawData.length > 0) {
                    let sortedData = Object.entries(rawData[0]).map(el => flattenObj(el)).sort((a, b) => a.startTime > b.startTime && 1 || -1);
                    playAdvertising(sortedData)
                }
            })
    };
    getAdvertising();
    let disconnects = 0;

    function connect(sessionIP = false) {
        let oldMessage = '';
        if (typeof (EventSource) !== "undefined") {
            let sseIP = null;
            if (sessionIP) {
                sseIP = `http://${sessionIP}:8080/`;
            } else {
                sseIP = "http://<?php echo $_SESSION['ip'] ?>:8080/";
            }
            let source = new EventSource(`${sseIP}tv/sse.php`);
            source.onmessage = function (event) {
                disconnects = 0;
                clearInterval(offlineInterval);
                if (oldMessage == event.data) return;
                oldMessage = event.data;
                console.log(event.data);
                let coordinates, div;

                switch (event.data.trim().substring(0, 3)) {
                    case 'lay':
                        fetch("<?php echo SERVER; ?>getnewlayout?location=<?php echo $_SESSION['id']; ?>")
                            .then(response => response.text())
                            .then(data => {
                                fetch("/tv/updateLocalLayoutData", {
                                    method: 'POST',
                                    body: JSON.stringify({'layout': data})
                                }).then(response => response.text()).then(data => console.log(data));
                                let info = JSON.parse(data);
                                document.querySelectorAll('.layoutElements').forEach(el => {
                                    el.remove();
                                });
                                console.log(Object.entries(info.save));
                                Object.entries(info.save).map(el => {
                                    switch (el[0]) {
                                        case "activate_logo":
                                            coordinates = el[1].split(",");
                                            div = document.createElement('div');
                                            div.id = "logoholder";
                                            div.style.left = coordinates[0] + '%';
                                            div.style.top = coordinates[1] + '%';
                                            div.classList.add('layoutElements');
                                            let img = document.createElement('img');
                                            img.src = SERVER + info.featured_image.replace('thumbnails', 'publications');
                                            div.appendChild(img);
                                            document.getElementById('screen-layout').appendChild(div);
                                            break;
                                        case 'activate_weather':
                                            coordinates = el[1].split(",");
                                            div = document.createElement('div');
                                            div.id = "weather";
                                            div.style.left = coordinates[0] + '%';
                                            div.style.top = coordinates[1] + '%';
                                            div.classList.add('layoutElements');
                                            div.innerHTML = '<span id="degree"></span><span>°C</span> <img id="icon" src="">';
                                            document.getElementById('screen-layout').appendChild(div);
                                            updateWeather(info.grad);
                                            clearInterval(weatherInterval);
                                            weatherInterval = setInterval(_ => {
                                                updateWeather(info.grad);
                                            }, 3600000);
                                            break;
                                        case 'active_scroll':
                                            coordinates = el[1].split(",");
                                            div = document.createElement('div');
                                            div.id = "weather";
                                            div.style.left = '0%';
                                            div.style.top = coordinates[1] + '%';
                                            div.style.width = '100vw';
                                            div.classList.add('layoutElements');
                                            div.innerHTML = `<div class="bar" style="background-color: ${info.save['bg_infodata']} ; color: ${info.save['txt_infodata']}">
                                                 <span class="bar_content">${info.activate_infodata}</span> </div>`;
                                            document.getElementById('screen-layout').appendChild(div);
                                            break;
                                        case 'activate_clock':
                                            clearTimeout(timerStart);
                                            coordinates = el[1].split(",");
                                            div = document.createElement('div');
                                            div.id = "clock";
                                            div.style.left = coordinates[0] + '%';
                                            div.style.top = coordinates[1] + '%';
                                            div.style.backgroundColor = info.save['bg_clock'];
                                            div.style.color = info.save['txt_clock'];
                                            div.classList.add('layoutElements', `${info.save['clock_size']}`);
                                            div.innerHTML = `<span id="add_time"></span>`;
                                            document.getElementById('screen-layout').appendChild(div);


                                            startTime();
                                            break;
                                    }
                                })
                            });
                        break;
                    case 'adv':
                        fetch('admin/getnewadvertising')
                            .then(response => response.text()).then(data => getAdvertising());
                        break;
                    case 'sch':
                        fetch('admin/getnewschedule')
                            .then(response => response.text())
                            .then(data => {
                                fetch("/tv/newSchedule",
                                    {
                                        headers: {
                                            'Accept': 'application/json',
                                            'Content-Type': 'application/json'
                                        },
                                        method: "POST",
                                        body: JSON.stringify({'day': days[currentDay]})
                                    })
                                    .then(response => response.text())
                                    .then(data => {
                                        let newPlayList = JSON.parse(data).map(el => el.video.replace('../public/', ''));
                                        nextsrc = newPlayList;
                                        // if (document.getElementById('noinfo')) {
                                        //     document.getElementById('noinfo').remove();
                                        //     newPlayer = document.createElement('video');
                                        //     newPlayer.id = 'player';
                                        //     newPlayer.muted = true;
                                        //     newPlayer.autoplay = 'autoplay';
                                        //     document.getElementById('appendPlayer').append(newPlayer);
                                        // }
                                        //   console.log(nextsrc[elm])
                                        elm = 0;
                                        document.getElementById('player').src = nextsrc[elm];
                                    });
                            });
                        break;
                }
            };
            source.onerror = function (e) {
                disconnects++;
                if (disconnects >= 3 || source.readyState == 2) {
                    offlineMode();
                    source.close();
                }
            };
        }
    }

    connect();
    let tempJSON = null;

    function getIP(json) {
        tempJSON = json;
        return tempJSON;
    }

    setInterval(_ => {
        fetch('admin/keepAlive')
            .then(response => response.text())
    }, 600000);
    let sessionIP = "<?php echo $_SESSION['ip'];?>";
    setInterval(_ => {
        let checkIP = getIP(tempJSON);
        let currentIP = checkIP.ip;
        // console.log(currentIP, sessionIP);
        if (currentIP != sessionIP) {
            fetch("/tv/updateIP",
                {
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    method: "POST",
                    body: JSON.stringify({'newIP': currentIP})
                }).then(response => response.text()).then(data => {
                sessionIP = currentIP;
                connect(sessionIP);
            })
        }
    }, 3600000);
    if (document.getElementById('clock')) {
        startTime()
    }


</script>
<script src="https://api.ipify.org?format=jsonp&callback=getIP"></script>