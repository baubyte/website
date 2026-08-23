import { route as ziggyRoute } from 'ziggy-js';
import { Ziggy } from '../ziggy.js';

/**
 * Project-configured Ziggy route helper.
 * Defaults to relative paths (e.g. '/locale/en') unless absolute = true is passed.
 *
 * @param {string} [name] Route name (e.g. 'locale', 'cv.download', 'home')
 * @param {any} [params] Route parameters
 * @param {boolean} [absolute] Whether to return absolute URL (default: false)
 * @param {any} [customConfig] Optional custom Ziggy config
 */
export function route(name, params, absolute = false, customConfig = Ziggy) {
    return ziggyRoute(name, params, absolute, customConfig);
}
