<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

</head>

<body>
    <script>      
        var width = 50;
        var space = "_";
        var animation_layer;
        var items;
        var bonus = false;
        var score=0;
        var highscore=0;

        // 🐥🥚🐤🐓🐦🍗🐣🐔

        function spawn_enemies(){
            if (Math.random() < 0.02) {
                spawn("🐓", "🐦", -1, width, true);
            } else if (Math.random() < 0.01) {
                spawn("🥚", "🐣", -1, width, true);
            }
        }

        function spawn(emoji, death, vector, position, deadly = false, life=false) {
            items.push(new Item(emoji, death, vector, position, deadly, life));
        }

        function Item(emoji, death, vector, position, deadly, life) {
            this.emoji = emoji;
            this.death = death;
            this.vector = vector;
            this.position = position + vector;
            this.deadly = deadly;
            this.life = life;
            this.alive = true;
        }

        function init() {
            score = 0;
            animation_layer = new Array(width).fill(space);
            items = [];
            spawn("🛸", "💥", 0, 0, false);
        }

        function is_alive() {
            if (highscore <= score) {
                highscore = score;
            }

            for (item of items) {
                if (item.emoji == "🛸") return true;
            }
            return false;
        }

        function game() {
            if (!is_alive()) {
                game_over();
                return;
            }
            display();
            animation_cycle();
            item_cycle();
            spawn_enemies();
        }

        var animations = {
            "🐥": "💥",
            "🐦": "💥",
            "💥": "💢",
        };

        function display() {
            var display = animation_layer;

            for (var position = 1; position < width; position++) {
                for (var i = 0; i < items.length; i++) {
                    if (items[i].position == position) {
                        display[position] = items[i].emoji;
                    }
                }
            }

            location.hash = display.join("");

            document.querySelector(".score").innerHTML = ("Score: "+score+" - Highscore: "+highscore);
        }

        function animation_cycle() {
            for (var i = 0; i < animation_layer.length; i++) {
                if (animation_layer[i]=="🐣") {
                    spawn("🐤", "🐥", -1, i, true);
                    animation_layer[i] = space;
                } else if (animation_layer[i]=="🐦"){

                    if (Math.random() < 0.2 && bonus) {
                        spawn("🍗", "🍗", -1, i, false, true);
                    } else {
                        animation_layer[i] = "💥";
                    }
                }
                else if (animation_layer[i] in animations) {
                    animation_layer[i] = animations[animation_layer[i]];
                } else {
                    animation_layer[i] = space;
                }
            }
        }

        function item_cycle() {
            for (var i = 0; i < items.length; i++) {
                var position = items[i].position;
                items[i].position += items[i].vector;
                
                if (collisions(items[i]) || items[i].position >= width || items[i].position < 0) {
                    if (items[i] && items[i].death) {
                        animation_layer[items[i].position] = items[i].death;
                    }
                    kill(items[i]);
                }
            }

            items = items.filter(item => item.alive);
        }

        function kill(item) {
            item.alive = false;
        }

        function collisions(item) {
            if (item.deadly)
                return false;

            var position = item.position;
            for (var i = 0; i < items.length; i++) {
                if (Math.abs(items[i].position-position) < 2 && items[i].deadly) {
                    if (items[i].death) {
                        animation_layer[items[i].position] = items[i].death;
                    }
                    score++;
                    kill(items[i]);
                    return true;
                }
            }
            return false;
        }

        document.onkeydown = checkKey;

        function checkKey(e) {
            e = e || window.event;

            if (e.keyCode == '39') { // Right arrow
                spawn("-", null, 1, 1, false);
            }

            if (!is_alive()) {
                init();
            }
        }

        function reverseString(str) {
            return str.split("").reverse().join("");
        }

        function game_over() {
            var i, n, wave = '';

            for (i = 0; i < 10; i++) {
                n = Math.floor(Math.sin((Date.now() / 200) + (i / 2)) * 4) + 4;
                wave += String.fromCharCode(0x2581 + n);
            }
            var string = wave + "[Game_Over]" + reverseString(wave);
            items = [];
            animation_layer = string.split("");
            display();
        }

        init();
        window.setInterval(game, 50);

    </script>

    <h2>Look at the URL ☝ <span class="score"></span></h2>
    <h1>
        Chicken Invaders<br>
        <b>Emoji Edition 🐔</b>
    </h1>
    <div class="instructions">Press ➡️ to shoot lasers !</div>
    Inspired by this cool <a href="https://matthewrayfield.com/articles/animating-urls-with-javascript-and-emojis/">blog post</a> by Matthew Rayfield.
    <br>
        <br>
    <div class="hire_me">
     Looking for a full-stack engineer ? <a href="https://arnaud.at/cv">Hire me !</a> 
    </div>
    <div class="bottom">
        (Of course not affiliated with Interaction Studios)
    </div>

    <a href="https://github.com/arnaudsm/emoji-url-game" class="github-corner" aria-label="View source on GitHub"><svg width="80" height="80" viewBox="0 0 250 250" style="fill:#151513; color:#fff; position: absolute; top: 0; border: 0; right: 0;" aria-hidden="true"><path d="M0,0 L115,115 L130,115 L142,142 L250,250 L250,0 Z"></path><path d="M128.3,109.0 C113.8,99.7 119.0,89.6 119.0,89.6 C122.0,82.7 120.5,78.6 120.5,78.6 C119.2,72.0 123.4,76.3 123.4,76.3 C127.3,80.9 125.5,87.3 125.5,87.3 C122.9,97.6 130.6,101.9 134.4,103.2" fill="currentColor" style="transform-origin: 130px 106px;" class="octo-arm"></path><path d="M115.0,115.0 C114.9,115.1 118.7,116.5 119.8,115.4 L133.7,101.6 C136.9,99.2 139.9,98.4 142.2,98.6 C133.8,88.0 127.5,74.4 143.8,58.0 C148.5,53.4 154.0,51.2 159.7,51.0 C160.3,49.4 163.2,43.6 171.4,40.1 C171.4,40.1 176.1,42.5 178.8,56.2 C183.1,58.6 187.2,61.8 190.9,65.4 C194.5,69.0 197.7,73.2 200.1,77.6 C213.8,80.2 216.3,84.9 216.3,84.9 C212.7,93.1 206.9,96.0 205.4,96.6 C205.1,102.4 203.0,107.8 198.3,112.5 C181.9,128.9 168.3,122.5 157.7,114.1 C157.9,116.9 156.7,120.9 152.7,124.9 L141.0,136.5 C139.8,137.7 141.6,141.9 141.8,141.8 Z" fill="currentColor" class="octo-body"></path></svg></a><style>.github-corner:hover .octo-arm{animation:octocat-wave 560ms ease-in-out}@keyframes octocat-wave{0%,100%{transform:rotate(0)}20%,60%{transform:rotate(-25deg)}40%,80%{transform:rotate(10deg)}}@media (max-width:500px){.github-corner:hover .octo-arm{animation:none}.github-corner .octo-arm{animation:octocat-wave 560ms ease-in-out}}</style>
    
    <style>
        body {
            font-family:'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;    
            margin: 0 5em;        
        }
        h1 {
            font-size: 5em;
            font-weight: 400;
        }
        h2 {
            font-weight: 400;
        }
        .instructions {
            font-size: 2em;
        }
        .score {
            margin-left: 5em;
            font-family: 'Courier New', Courier, monospace;
        }
        .bottom {
            position: absolute;
            bottom: 0;
            right: 0;
            margin: 1em;
        }
        .hire_me {
            position: absolute;
            bottom: 0;
            left: 0;
            margin: 1em 5em;
        }
    </style>
</body>

</html>