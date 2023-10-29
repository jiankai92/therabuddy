/**
 * Converts a string value containing px to an absolute Int value
 * 
 * @param value property value with px eg: '100px'
 * @return int property value as int eg: 100
 */
export function convertPxToAbsolute(value) {
    return parseInt(value.replace('px', ''));
}

/**
 * Converts tailwind default spacing value to value is px
 *
 * @param value Tailwind class spacing value eg: 24
 * @return int Corresponding px value eg: 96
 */
export function tailwindSpacingToPx(value) {
    return parseInt(value) * 4
}

/**
 * Convert Tailwind class value to corresponding value in px
 * @param className Tailwind class name eg: h-24 or h-[96px]
 * @returns {int|number} Property px value eg: 96
 */
export function convertTailwindClassValueToPx (className) {
    let value = className.split("-")[1];
    if (value.includes('px')) {
        return convertPxToAbsolute(value.replace("[", "").replace("]",""));
    } else {
        return tailwindSpacingToPx(value);
    }
}