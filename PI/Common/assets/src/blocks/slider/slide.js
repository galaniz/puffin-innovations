/**
 * Slide block
 */

/* Dependencies */

const {
  getNamespace,
  getNamespaceObj
} = window.blockUtils

const {
  Panel,
  PanelBody,
  TextControl
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
const name = n + 'slide'

/* Attributes from serverside */

const nO = getNamespaceObj(getNamespace())
const attr = nO.blocks[name].attr
const def = nO.blocks[name].default

/* Set id and index */

const dataSelector = withSelect((select, ownProps) => {
  const { clientId } = ownProps
  const { getBlockIndex } = select('core/block-editor')

  ownProps.attributes.id = clientId
  ownProps.attributes.index = getBlockIndex(clientId)
})

/* Block */

registerBlockType(name, {
  title: 'Slide',
  category: 'theme-blocks',
  icon: 'slides',
  attributes: attr,
  parent: [n + 'slider'],
  edit: dataSelector((props) => {
    const { attributes, setAttributes } = props
    const { internal_name = def.internal_name } = attributes // eslint-disable-line camelcase

    /* Internal name */

    const internalName = internal_name // eslint-disable-line camelcase

    /* Output */

    return [
      <Fragment key='frag'>
        <InspectorControls>
          <PanelBody title='Slide Options'>
            <TextControl
              label='Internal Name'
              help='For editor organization'
              value={internal_name} // eslint-disable-line camelcase
              onChange={v => setAttributes({ internal_name: v })}
            />
          </PanelBody>
        </InspectorControls>
      </Fragment>,
      <Panel key='panel'>
        <PanelBody title={`Slide${internalName ? `: ${internalName}` : ''}`} initialOpen={false}>
          <InnerBlocks />
        </PanelBody>
      </Panel>
    ]
  }),
  save () {
    return <InnerBlocks.Content /> // Rendered in php
  }
})
