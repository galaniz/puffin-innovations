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
  TextControl,
  SelectControl
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

/* Allowed blocks */

let allowedBlocks

/* Attributes from serverside */

const nO = getNamespaceObj(getNamespace())
const attr = nO.blocks[name].attr
const def = nO.blocks[name].default
const sliderType = n + 'slider/type'

let usesContext = []

if (Object.getOwnPropertyDescriptor(nO.blocks[name], 'uses_context')) {
  usesContext = nO.blocks[name].uses_context
}

/* Set id and index */

const dataSelector = withSelect((select, ownProps) => {
  const { clientId } = ownProps
  const { getBlockIndex } = select('core/block-editor')

  ownProps.attributes.id = clientId
  ownProps.attributes.index = getBlockIndex(clientId)
  ownProps.attributes.length = select('core/block-editor').getBlocks(clientId).length
})

/* Block */

registerBlockType(name, {
  title: 'Slide',
  category: 'theme-blocks',
  icon: 'slides',
  attributes: attr,
  parent: [n + 'slider'],
  usesContext,
  edit: dataSelector((props) => {
    const { attributes, setAttributes, context } = props

    const {
      internal_name = def.internal_name, // eslint-disable-line camelcase
      title = def.title,
      title_tag = def.title_tag // eslint-disable-line camelcase
    } = attributes

    /* Internal name */

    const internalName = internal_name // eslint-disable-line camelcase

    /* Type */

    let displayTitle = false

    if (Object.getOwnPropertyDescriptor(context, sliderType)) {
      displayTitle = context[sliderType] === 'group-flex'
      allowedBlocks = [n + 'slide-content']
    }

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
            {displayTitle && (
              <Fragment>
                <TextControl
                  label='Title'
                  value={title}
                  onChange={title => setAttributes({ title })}
                />
                <SelectControl
                  label='Title Tag'
                  value={title_tag} // eslint-disable-line camelcase
                  options={[
                    { label: 'Heading Two', value: 'h2' },
                    { label: 'Heading Three', value: 'h3' },
                    { label: 'Heading Four', value: 'h4' },
                    { label: 'Heading Five', value: 'h5' },
                    { label: 'Heading Six', value: 'h6' }
                  ]}
                  onChange={v => setAttributes({ title_tag: v })}
                />
              </Fragment>
            )}
          </PanelBody>
        </InspectorControls>
      </Fragment>,
      <Panel key='panel'>
        <PanelBody title={`Slide${internalName ? `: ${internalName}` : ''}`} initialOpen={false}>
          <InnerBlocks allowedBlocks={allowedBlocks} />
        </PanelBody>
      </Panel>
    ]
  }),
  save () {
    return <InnerBlocks.Content /> // Rendered in php
  }
})
