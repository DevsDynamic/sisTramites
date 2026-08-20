export async function get(url)
{
    return axios.get(url);
}

export async function post(url,data)
{
    return axios.post(url,data);
}

export async function put(url,data)
{
    return axios.post(url,data);
}

export async function destroy(url)
{
    return axios.delete(url);
}