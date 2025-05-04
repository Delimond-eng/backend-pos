/***
 * Fait passer les requêtes HTTP en GET ou en POST,
 * si data est défini c'est la requête POST qui sera lancée,
 * autrement c'est la requête GET qui sera lancée
 * @param {String} [url=null]
 * @param {Object} form
 * @returns {data, status} data: http response if status equal 200 or 201
 */
export async function post(url, form) {
    var csrfToken = document
        .querySelector('meta[name="csrf-token"]')
        .getAttribute("content");
    const response = await fetch(url, {
        method: "POST",
        headers: {
            "X-CSRF-TOKEN": csrfToken,
        },
        body: form,
    });
    const data = await response.json();
    return { data, status: response.status };
}
/***
 * Fait passer les requêtes HTTP en GET ou en POST,
 * si data est défini c'est la requête POST qui sera lancée,
 * autrement c'est la requête GET qui sera lancée
 * @param {String} [url=null]
 * @param {Object} form
 * @returns {data, status} data: http response if status equal 200 or 201
 */
export async function postJson(url, form) {
    const csrfToken = document
        .querySelector('meta[name="csrf-token"]')
        .getAttribute("content");

    const response = await fetch(url, {
        method: "POST",
        headers: {
            "X-CSRF-TOKEN": csrfToken,
            "Content-Type": "application/json",
            Accept: "application/json",
        },
        body: JSON.stringify(form),
    });

    let data;
    try {
        data = await response.json();
    } catch (e) {
        const text = await response.text(); // utile pour debugger
        throw new Error("Réponse non-JSON reçue :\n" + text);
    }

    return { data, status: response.status };
}

/**
 * Fait une requete en GET
 * @param {*} url
 * @returns {data, status} data: http response if status equal to 200 or 201
 */
export async function get(url) {
    const response = await fetch(url, {
        method: "GET",
        headers: {
            "Content-Type": "application/json",
        },
    });
    const data = await response.json();
    return { data, status: response.status };
}
