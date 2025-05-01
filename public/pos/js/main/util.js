export function cdfToDollars(taux, cdf) {
    const tauxCDF = parseFloat(taux);
    const dollars = cdf / tauxCDF;
    return dollars;
}
export function dollarsToCdf(taux, dollars) {
    const tauxCDF = parseFloat(taux);
    const cdf = dollars * tauxCDF;
    return cdf;
}