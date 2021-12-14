$(document).ready(() => {
    //mySocket
    var conn = new WebSocket('ws://localhost:8080');
    function getAllMessages() {
        $.ajax({
            url: 'get-messages.php',
            dataType: 'json',
            success: function (res) {
                if (res)
                    res.map(chat => {
                        $('.chat__messages').prepend(`
                    <div class="chat__message_${chat.username != username ? 'not_' : ''}user">
                <div  class= "message__profile" > ${chat.username}</div >
                <div class="message__text">${chat.msg}</div>
                    </div >
                    `)
                    })
            }
        })
    }
    let username = null
    // Set the user
    if (sessionStorage.getItem('username')) {
        username = sessionStorage.getItem('username')
    } else {
        username = prompt('Your username please :')
        sessionStorage.setItem('username', username)
    }

    // Variables
    const messageInput = $('.chat__input')
    const chatMessagesContainer = $('.chat__messages')
    const sendButton = $('.send_btn')

    // Events
    sendButton.on('click', sendMessage)


    // Actions
    function sendMessage() {
        $('.chat__messages').prepend(`
                    <div div class= "chat__message_user" >
            <div class="message__profile">${username}</div>
            <div class="message__text">${messageInput.val()}</div>
        </div >
                    `)
        conn.send(JSON.stringify({
            username,
            message: messageInput.val()
        }))
        messageInput.val('')
    }

    conn.onmessage = function (e) {

        res = JSON.parse(e.data)
        $('.chat__messages').prepend(`
                    <div div class= "chat__message_not_user" >
            <div class="message__profile">${res.username}</div>
            <div class="message__text">${res.message}</div>
        </div >
                    `)
    }
    getAllMessages()


})
