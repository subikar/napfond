<script type="text/javascript" src="instagram-javascript-sdk-master/ig.js"></script>
<script type="text/javascript">

IG.init({
    client_id: '36f371f423a545eba474a641dca534ec',
    check_status: true, // check and load active session
    cookie: true // persist a session via cookie
});

// client side access_token flow (implicit)
IG.login(function (response) {
    if (response.session) {
        // user is logged in
    }
	else{
	alert("Not Logged In")
	}
}, {scope: ['comments', 'likes']});
</script>