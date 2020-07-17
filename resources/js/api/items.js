export function findItems(queryParams) {
    return axios({
        url: '/stock/item/api/v1/find-item',
        method: 'get',
        params: queryParams,
    })
}

export function findItemInformation(queryParams) {
    return axios({
        url: '/stock/item/api/v1/find-item-info',
        method: 'get',
        params: queryParams,
    })
}

export function findItemBorrows(queryParams) {
    return axios({
        url: '/stock/item/api/v1/find-item-borrows',
        method: 'get',
        params: queryParams,
    })
}
