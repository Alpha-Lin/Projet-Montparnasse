function rating(id) {
    let rates = document.getElementsByClassName('rate')

    for (let i = 0; i < rates.length; i++)
        rates[i].hidden = true
    
    let rate_id = 'rate_' + id;

    document.getElementById(rate_id).hidden = false

    document.location = document.location.toString().split('#')[0] + '#' + rate_id
}
