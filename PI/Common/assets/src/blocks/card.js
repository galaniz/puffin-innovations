/**
 * Card block
 */

/* Dependencies */

const {
  getNamespace,
  getNamespaceObj
} = window.blockUtils

const {
  Panel,
  PanelBody,
  CheckboxControl
} = window.wp.components

const {
  InspectorControls,
  InnerBlocks
} = window.wp.blockEditor

const { withSelect } = window.wp.data
const { Fragment } = window.wp.element
const { registerBlockType } = window.wp.blocks

/* Namespace */

const n = getNamespace(true)
const name = n + 'card'

/* Allowed blocks */

let allowedBlocks = ['image', 'text']

allowedBlocks = allowedBlocks.map(b => n + b)

/* Attributes from serverside */

const nO = getNamespaceObj(getNamespace())
const attr = nO.blocks[name].attr
const def = nO.blocks[name].default

/* Set exists */

const dataSelector = withSelect((select, ownProps) => {
  ownProps.attributes.exists = true
})

/* Block */

registerBlockType(name, {
  title: 'Card',
  category: 'theme-blocks',
  icon: 'media-default',
  attributes: attr,
  edit: dataSelector((props) => {
    const { attributes, setAttributes } = props
    const { border = def.border } = attributes

    /* Output */

    return [
      <Fragment key='frag'>
        <InspectorControls>
          <PanelBody title='Card Options'>
            <CheckboxControl
              label='Border'
              help='Wrap in blue border'
              value='1'
              checked={!!border}
              onChange={border => setAttributes({ border })}
            />
          </PanelBody>
        </InspectorControls>
      </Fragment>,
      <Panel key='panel'>
        <PanelBody title='Card' initialOpen={false}>
          <InnerBlocks
            templateLock={false}
            allowedBlocks={allowedBlocks}
          />
        </PanelBody>
      </Panel>
    ]
  }),
  save () {
    return <InnerBlocks.Content /> // Rendered in php
  }
})
