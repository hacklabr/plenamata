import { registerBlockType } from "@wordpress/blocks";
import { __ } from "@wordpress/i18n";

import Editor from "./editor";

registerBlockType('jaci/estivatives-area', {
    title: __('Estimatives Area', 'jaci'),
    icon: 'visibility',
    category: 'common',
    keywords: [
    ],
    supports: {
        align: false,
    },
    attributes: {
        // Strings
        boxTitle: {
            type: "string"
        },
        headingTitle: {
            type: "string"
        },
        preNumberTitle: {
            type: "string"
        },
        averageTitle: {
            type: "string"
        },
        deforestedTitle: {
            type: "string"
        },
        finalInformation: {
            type: "string"
        },

        // Base numbers
        baseTrees: {
            type: "string"
        },
        tressPerDay: {
            type: "string"
        },
        hecPerDay: {
            type: "string"
        },
        hectares: {
            type: "string"
        },
        warnings: {
            type: "string"
        },
        baseDate: {
            type: "string"
        }
    },

    edit: Editor,

    save: ({ attributes }) => {
        return (
            null
        )
    },
});