function BytesToString(Bytes)
{
    var str = '';
    for(var i=0; i<Bytes.length ;++i)
        str += String.fromCharCode(Bytes[i]);

    return str;
}

function StringToByte(str)
{
    var rt = new Uint8Array(str.length);
    for(var i=0; i<rt.length ;++i)
        rt[i] = str.charCodeAt(i);
    return rt;
}

function Base64ToBytes(str)
{
    var data = window.atob(str);
    return StringToByte(data);
}

function decrypt(encrypted_str, key)
{
    var encrypted_json = JSON.parse(window.atob(encrypted_str));
    var iv_arr = Base64ToBytes(encrypted_json.iv);
    var key_arr = Base64ToBytes(key);
    var val_arr = Base64ToBytes(encrypted_json.value);

    var aesCbc = new aesjs.ModeOfOperation.cbc(key_arr, iv_arr);
    var decryptedBytes = aesCbc.decrypt(val_arr);

    var str = '';
    for(var i=0; i<decryptedBytes.length ;++i)
        str += String.fromCharCode(decryptedBytes[i]);
    
    return str;
}

/**
 * str need serialize with php way.
 */
function encrypt(str, key)
{
    var iv = new Uint8Array(16);
    var ENCRYPT_PADDING = 2;

    for(var i=0; i<16 ;++i)
        iv[i] = Math.floor(Math.random() * 255);

    var key_arr = Base64ToBytes(key);

    var str_arr = new Uint8Array( (Math.floor(str.length/16)+1) * 16 );
    for(var i=0; i<str_arr.length ;++i)
    {
        if(i < str.length)
            str_arr[i] = str.charCodeAt(i);
        else
            str_arr[i] = 2;
    }
    var aesCbc = new aesjs.ModeOfOperation.cbc(key_arr, iv);
    var encryptedBytes = aesCbc.encrypt(str_arr);
    var encryptStr = window.btoa(BytesToString(encryptedBytes));

    var mac = CryptoJS.HmacSHA256(window.btoa(BytesToString(iv)) + encryptStr, key);

    var json = {
        "iv" : window.btoa(BytesToString(iv)),
        "value" : encryptStr,
        "mac" : mac.toString()
    };
    var js_serialize = JSON.stringify(json);
    return window.btoa(js_serialize);
}