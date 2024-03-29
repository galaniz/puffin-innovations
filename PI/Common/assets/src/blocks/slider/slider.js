/**
 * Slider block
 */

/* Dependencies */

const {
  getNamespace,
  getNamespaceObj
} = window.blockUtils

const {
  Panel,
  PanelBody,
  SelectControl,
  TextControl,
  RadioControl
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
const name = n + 'slider'

/* Allowed blocks */

let allowedBlocks = ['slide']

allowedBlocks = allowedBlocks.map(b => n + b)

const template = allowedBlocks.map(b => {
  return [b, {}, []]
})

/* Attributes from serverside */

const nO = getNamespaceObj(getNamespace())
const attr = nO.blocks[name].attr
const def = nO.blocks[name].default

/* Set data */

const dataSelector = withSelect((select, ownProps) => {
  const { clientId } = ownProps

  const ids = []
  const titles = []
  const tabTexts = []

  const blocks = select('core/block-editor').getBlocks(clientId)

  if (blocks.length) {
    blocks.forEach((block) => {
      const { clientId, attributes } = block
      const { title = '', tab_text = '' } = attributes // eslint-disable-line camelcase

      ids.push(clientId)
      titles.push(title)
      tabTexts.push(tab_text)
    })
  }

  ownProps.attributes.length = blocks.length

  if (ids.length) {
    ownProps.attributes.ids = ids.join(',')
  }

  if (titles.length) {
    ownProps.attributes.titles = titles.join(',')
  }

  if (tabTexts.length) {
    ownProps.attributes.tab_texts = tabTexts.join(',')
  }
})

/* Block */

registerBlockType(name, {
  title: 'Slider',
  category: 'theme-blocks',
  icon: 'slides',
  attributes: attr,
  edit: dataSelector(props => {
    const { attributes, setAttributes } = props

    const {
      label = def.label,
      width = def.width,
      type = def.type
    } = attributes

    /* Output */

    return [
      <Fragment key='frag'>
        <InspectorControls>
          <PanelBody title='Slider Options'>
            <TextControl
              label='Label'
              help={`Screen reader text for slides and buttons. Slide: ${label || '{label}'} group {index}. Button: Go to ${label || '{label}'} group {index}`}
              value={label}
              onChange={label => setAttributes({ label })}
            />
            <RadioControl
              label='Type'
              selected={type}
              options={[
                { label: 'Group', value: 'group' },
                { label: 'Group Flex', value: 'group-flex' }
              ]}
              onChange={type => setAttributes({ type })}
            />
            <SelectControl
              label='Slide Width'
              value={width}
              options={[
                { label: '100%', value: '100%' },
                { label: '50%', value: '50%' },
                { label: '33%', value: '33%' },
                { label: '25%', value: '25%' }
              ]}
              onChange={width => setAttributes({ width })}
            />
          </PanelBody>
        </InspectorControls>
      </Fragment>,
      <Panel key='panel'>
        <PanelBody title='Slider' initialOpen={false}>
          <InnerBlocks
            templateLock={false}
            allowedBlocks={allowedBlocks}
            template={template}
          />
        </PanelBody>
      </Panel>
    ]
  }),
  save () {
    return <InnerBlocks.Content /> // Rendered in php
  }
})
