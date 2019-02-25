<html>
<body>
    <script type="text/javascript">
        window.opener.Events.$emit('user-authenticated', JSON.stringify({!! Auth::user() ? json_encode(Auth::user()->toArrayForCookie()) : null  !!}));
        window.close();
    </script>
</body>
</html>