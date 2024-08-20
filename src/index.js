/**
 * WordPress dependencies
 */
import { addFilter } from "@wordpress/hooks";
import { createHigherOrderComponent } from "@wordpress/compose";
import { InspectorControls } from "@wordpress/block-editor";
import { PanelBody, ToggleControl } from "@wordpress/components";

/**
 * Add the core block attribute needed for centering on mobile.
 */
function addAttributes(settings) {
	if ("core/group" !== settings.name) {
		return settings;
	}

	const blockAttributes = {
		isCenteredOnMobile: {
			type: "boolean",
			default: false,
		},
	};

	const newSettings = {
		...settings,
		attributes: {
			...settings.attributes,
			...blockAttributes,
		},
	};

	return newSettings;
}

addFilter(
	"blocks.registerBlockType",
	"wp-center-on-mobile/add-attributes",
	addAttributes,
);

/*
 * Add an Inspector Controls panel for the Center Text on Mobile toggle
 */
function addInspectorControls(BlockEdit) {
	return (props) => {
		if (props.name !== "core/group") {
			return <BlockEdit {...props} />;
		}

		const { attributes, setAttributes } = props;
		const { isCenteredOnMobile } = attributes;

		return (
			<>
				<BlockEdit {...props} />
				<InspectorControls>
					<PanelBody title={"Mobile Settings"}>
						<div className="enable-center-container">
							<ToggleControl
								label={"Center text on mobile"}
								checked={isCenteredOnMobile}
								onChange={() => {
									setAttributes({
										isCenteredOnMobile: !isCenteredOnMobile,
									});
								}}
							/>
						</div>
					</PanelBody>
				</InspectorControls>
			</>
		);
	};
}

addFilter(
	"editor.BlockEdit",
	"wp-center-on-mobile/add-inspector-controls",
	addInspectorControls,
);

/**
 * Add the class when Center text on mobile toggle is on
 */

const addCenteredOnMobile = createHigherOrderComponent((BlockListBlock) => {
	return (props) => {
		const { attributes, name } = props;

		if (name !== "core/group") {
			return <BlockListBlock {...props} />;
		}

		return (
			<BlockListBlock
				{...props}
				className={attributes.isCenteredOnMobile ? "is-centered-on-mobile" : ""}
			/>
		);
	};
}, "withCenteredOnMobile");

addFilter(
	"editor.BlockListBlock",
	"wp-center-on-mobile/add-editor-class",
	addCenteredOnMobile,
);
