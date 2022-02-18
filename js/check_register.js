function check_pass() {
    if (document.getElementById('mdp').value == document.getElementById('conf_mdp').value)
        document.getElementById('submit').disabled = false;
    else
        document.getElementById('submit').disabled = true;
}
