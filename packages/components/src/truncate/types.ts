/**
 * External dependencies
 */
import type { ReactNode } from 'react';

export type TruncateEllipsizeMode =
	| 'auto'
	| 'head'
	| 'tail'
	| 'middle'
	| 'none';

export type TruncateProps = {
	/**
	 * The ellipsis string when `truncate` is set.
	 *
	 * @default '…'
	 */
	ellipsis?: string;
	/**
	 * Determines where to truncate.  For example, we can truncate text right in
	 * the middle. To do this, we need to set `ellipsizeMode` to `middle` and a
	 * text `limit`.
	 *
	 * * `auto`: Trims content at the end automatically without a `limit`.
	 * * `head`: Trims content at the beginning. Requires a `limit`.
	 * * `middle`: Trims content in the middle. Requires a `limit`.
	 * * `tail`: Trims content at the end. Requires a `limit`.
	 *
	 * @default 'auto'
	 */
	ellipsizeMode?: TruncateEllipsizeMode;
	/**
	 * Determines the max characters when `truncate` is set.
	 *
	 * @default 0
	 */
	limit?: number;
	/**
	 * Clamps the text content to the specified `numberOfLines`, adding the
	 * `ellipsis` at the end.
	 *
	 * @default 0
	 */
	numberOfLines?: number;
	/**
	 * The children elements.
	 */
	children: ReactNode;
};
