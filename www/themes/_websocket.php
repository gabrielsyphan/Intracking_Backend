<?php if(isset($_SESSION['user']['name'])): ?>
    <script src="https://websocket-orditi.herokuapp.com/socket.io/socket.io.js"></script>
    <script>
        let messages = [];
        let geolocations = [];

        const socket = io('https://websocket-orditi.herokuapp.com/');
        socket.nickname = '';

        $(function () {
            submeterForm(socket);

            if(localStorage.getItem('messages')) {
                JSON.parse(localStorage.getItem('messages')).forEach(element => {
                    messages.push(element);

                    if (element.user == socket.nickname) {
                        $('.chat-messages').append($('<div class="chat-bubble me">').text(element.formated_user + ': ')
                            .append($('<span class="span-message-wrap">').text(element.msg)));
                    } else {
                        $('.chat-messages').append($('<div class="chat-bubble you">').text(element.formated_user + ': ')
                            .append($('<span class="span-message-wrap">').text(element.msg)));
                    }
                });
            }

            $('#send-message').click(() => sendMessage(socket));
            $('#input-message').keypress(function (e) {
                if (e.which === 13) {
                    if ($('#input-message').val()) {
                        sendMessage(socket);
                    }
                }
            });
            $('#chat-clear').click(() => {
                localStorage.clear();
                $('.chat-messages').empty();
                $('.chat-start').remove();
                $(".chat-messages").append("<div class='text-center mt-5 pt-5 div-image'><img src='<?= url("/themes/assets/img/empty.svg") ?>' style='width: 80%;'> <h5 class='mt-3'>Nenhuma mensagem recebida.</h5></div>");
            });

            socket.on('chat msg', showMsg);
            socket.on('geolocation', showGeolocation);
        });

        function sendMessage(socket) {
            if($('.chat-messages .div-image').length > 0){
                $('.chat-messages').empty();
            }

            socket.emit('chat msg', {date: new Date(), msg: $('#input-message').val()});
            $('#input-message').val('');
        }

        function showGeolocation(geolocation) {
            geolocations.push({
                user: geolocation.user,
                lat: geolocation.lat,
                lng: geolocation.lng
            });
        }

        function showMsg(msg) {
            let userName = msg.user.split(' ');
            if (userName[1]) {
                userName = userName[0] + ' ' + userName[1];
            } else {
                userName = userName[0];
            }

            messages.push({
                formated_user: userName,
                user: msg.user,
                msg: msg.msg
            });

            localStorage.setItem('messages', JSON.stringify(messages));

            if($('.chat-messages .div-image').length > 0){
                $('.chat-messages').empty();
            }

            if (msg.user == socket.nickname) {
                $('.chat-messages').append($('<div class="chat-bubble me">').text(userName + ': ')
                    .append($('<span class="span-message-wrap">').text(msg.msg)));
            } else {
                $('.chat-messages').append($('<div class="chat-bubble you">').text(userName + ': ')
                    .append($('<span class="span-message-wrap">').text(msg.msg)));
            }
        }

        function submeterForm(socket) {
            if (socket.nickname === '') {
                socket.nickname = '<?= $_SESSION['user']['name'] ?>';
                socket.emit('login', socket.nickname);
            }
            return false
        }
    </script>
<?php endif; ?>
